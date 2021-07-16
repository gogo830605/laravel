<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Http\Request;

class ApiLoginController extends Controller
{
    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => 400]);
        }

        //認證欄位
        $email = filter_var(trim($request->email), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($request->password), FILTER_SANITIZE_STRING);
        $credentials = ['email' => $email, 'password' => $password];

        if (Auth::attempt($credentials, false)) {
            $user = Auth::user();
//            dd($user->createToken('ss')->accessToken);
            $apiToken = $user->createToken('ticket')->accessToken;
            header('Authorization: Bearer ' . $apiToken);
            return response()->json(['message' => 'login OK', 'status' => 200]);
        } else {
            return response()->json(['message' => 'login error', 'status' => 400]);
        }
    }
}
