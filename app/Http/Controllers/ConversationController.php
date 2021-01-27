<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\NewMessage;
use App\Group;
use App\Message;
use App\Notifications\MessageCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(Group $group)
    {
        //$groups = auth()->user()->groups;
        $loggedUser = auth()->user()->id;
        if ($group->hasUser($loggedUser)){
            return view('conversation',['group'=>$group]);
        }else{
            return redirect('/home');
        }
    }

    /*public function index()
    {
        return Message::all();
    }*/

    public function show(Group $group)
    {
        $conversation = $group->conversations;
        $conversation->load('user');
        return $conversation;
    }

    public function store(Request $request, Group $group)
    {
        $conversation = Conversation::create([
            'message' => request('message'),
            'group_id' => request('group_id'),
            'user_id' => auth()->user()->id,
        ]);

        foreach ($group->users->where('id','!=', auth()->user()->id) as $user){
            $user->notify(new MessageCreated($user->id,$group->id));
        }

        broadcast(new NewMessage($conversation));

        return $conversation->load('user');
    }

    public function bruteforce()
    {
        $conversation = Message::create([
            'message' => 'bruteforce Message',
            'user' => 'bruteForceUser'
        ]);

        broadcast(new NewMessage($conversation));
    }
}
