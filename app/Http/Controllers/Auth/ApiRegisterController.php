<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\Validator;
use App\Repositories\RegisterRepository;
use Illuminate\Http\Request;

class ApiRegisterController extends Controller
{
    private $model;
    public function __construct(RegisterRepository $model)
    {
        $this->model = $model;
    }

    protected function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => 400]);
        }
        $request_all = $request->all();
        if ($member = $this->model->saveRegister($request_all)) {
            return response()->json(['message' => 'register OK', 'status' => 200]);
        } else {
            return response()->json(['message' => 'register error', 'status' => 400]);
        }
    }
}
