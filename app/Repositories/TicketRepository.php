<?php
namespace App\Repositories;

use App\Models\Ticket;
use App\Models\UserTicket;
use App\Commons\APIException;
use App\Traits\RedisLock;

use DB, Auth;

class TicketRepository
{
    use RedisLock;

    private $model;
    public function __construct()
    {
        $this->model = new Ticket;
    }

    public function getList()
    {
        return $this->model
            ->select('sort', 'name', 'amount')
            ->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>', date('Y-m-d'))
            ->get();
    }

    public function saveTicket($arr)
    {
        try {
            $pdo = DB::connection()->getPdo();
            $pdo->exec('LOCK TABLES tickets WRITE');
            if (Ticket::lockForUpdate()->where('name', $arr['name'])->first())
            {
                $pdo->exec( 'UNLOCK TABLES' );
                return false;
            }
            $this->model->lockForUpdate()->create($arr);

            $pdo->exec('UNLOCK TABLES');

        } catch (\Exception $e) {

            throw new ApiException($e->getMessage(), 500);
        }

        return true;
    }

    public function minusTicketAmount($arr)
    {
        try {
            //redis鎖
            $rand = $this->random_str();
            if (! $this->lock($rand)) return ['message' => 'wait a minute'];

            DB::beginTransaction();
            $ticket = Ticket::find($arr['id']);

            //判斷庫存是否足夠
            $number =  $ticket->amount;
            if ($number <= 0) {
                $this->unlock($rand);
                return ['message' => 'fail: Ticket Not Enough'];
            } elseif ($number < $arr['amount']) {
                $this->unlock($rand);
                return ['message' => 'fail: Ticket Stock Only '.$number];
            }

            Ticket::lockForUpdate()->find($arr['id'])->decrement('amount', $arr['amount']);

            //紀錄會員買的票
            $data = new UserTicket;
            $data['user_id'] = Auth::id();
            $data['ticket_name'] = $ticket->name;
            $data['amount'] = $arr['amount'];
            $data->save();

        } catch (\Exception $e) {
            DB::rollback();
            $this->unlock($rand);
            throw new ApiException($e->getMessage(), 500);
        }
        DB::commit();
        $this->unlock($rand);

        return ["name" => $ticket->name];
    }

    public function getUserTicket()
    {
        return UserTicket::where('user_id', Auth::id())->get();
    }

    function random_str($length=8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i=0; $i < $length; $i++){
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
