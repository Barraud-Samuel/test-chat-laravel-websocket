<?php

namespace App\Http\Controllers;

use App\Events\GroupCreated;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $loggedUser = Auth::user();
        $loggedUserGroups =  $loggedUser->groups;

        $allUsers = User::where('id','!=', $loggedUser->id)->get();

        return response()->json([
            'LoggedUser' => $loggedUser,
            'LoggedUserGroups' => $loggedUserGroups,
            'AllUsers' => $allUsers
        ]);
    }

    public function store(Request $request)
    {
        $group = Group::create(['name' => $request->name]);

        $users = collect($request->users);
        $users->push(auth()->user()->id);

        $group->users()->attach($users);

        broadcast(new GroupCreated($group));

        return response()->json([
            'Group' => $group
        ]);

        //return $group;
    }
}
