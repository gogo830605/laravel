<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Http\Request;
//use Auth, Validator;

class LoginController extends Controller
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
     /*
     |--------------------------------------------------------------------------
     | Login Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */

//     use AuthenticatesUsers;
//
//     /**
//      * Where to redirect users after login.
//      *
//      * @var string
//      */
//     protected $redirectTo = RouteServiceProvider::HOME;
//
//     /**
//      * Create a new controller instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         $this->middleware('guest')->except('logout');
//     }
}
