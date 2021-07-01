<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\TicketRepository;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Auth, Validator;


class TicketController extends Controller
{
    private $model;
    public function __construct(TicketRepository $model)
    {
        $this->model = $model;
    }

    public function list()
    {
        return response()->json($this->model->getList());
    }

    public function add(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'sort' => 'required',
//            'name' => 'required|unique:tickets,name',
//            'amount' => 'required'
//        ]);
//        if ($validator->fails()) {
//            return response()->json(['message' => $validator->messages(), 'status' => 400]);
//        }
        $request_all = $request->all();
        if ($this->model->saveTicket($request_all)) {
            return response()->json(['message' => 'insert OK', 'status' => 200]);
        }
    }

    public function buyTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => [
                    'required',
                    Rule::exists('tickets')->where(function ($query) use ($request) {
                        $query->where('id', $request->id)
                            ->where('start_date', '<=', date('Y-m-d'))
                            ->where('end_date', '>', date('Y-m-d'));
                    })
                ],
            'amount' => 'required|numeric|min:1|max:2',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => 400]);
        }

        $tickt = $this->model->minusTicketAmount($request->all());

        if (isset($tickt['message'])) {
            return response()->json(['message' => $tickt['message'], 'status' => 400]);
        }
        return response()->json(['message' => 'OK', 'status' => 200]);
    }

    public function userTicket()
    {
        return $this->model->getUserTicket();
    }
}
