<?php

namespace App\Http\Controllers;

use App\Events\PushNotification;
//use App\Message;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $message = auth()->user()->messages()->create([
            'message' => $request->message
        ]);
		broadcast(new PushNotification($message->load('user')))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
