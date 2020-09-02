<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        return Message::all();
    }

    public function bruteforce()
    {
        $conversation = Message::create([
           'message' => 'bruteforce Message',
           'user' => 'bruteForceUser'
       ]);

        broadcast(new NewMessage($conversation));
    }


    public function store(Request $request)
    {
        $request->validate([
            'message'=>'required'
        ]);

        $random_usernames = [
            'Anonymous',
            'Someone',
            'Some Girl',
            'Some Boy',
            'Mr. X',
            'Mr. Y',
            'Mr. ABC',
            'Ms. A',
            'Ms. B',
            'Ms. C',
        ];

        $conversation = Message::create([
            'message' => $request->message,
            'user' => $random_usernames[array_rand($random_usernames)],
        ]);

        broadcast(new NewMessage($conversation));
    }
}
