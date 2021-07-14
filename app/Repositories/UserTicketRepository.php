<?php
namespace App\Repositories;

use App\Models\UserTicket;
use Str;

class UserTicketRepository
{
    private $model;
    public $table_name;
    public function __construct()
    {
        $this->model = new UserTicket;
    }

    public function saveUserTicket($arr)
    {
        $data = new $this->model;
        $data['sort'] = $arr['sort'];
        $data['name'] = $arr['name'];
        $data['amount'] = $arr['amount'];
        // $data['email_verified_at'] = Str::random(10);

        // update table event
        $status = $data->save();
        return ($status)?$data:false;
    }
}
