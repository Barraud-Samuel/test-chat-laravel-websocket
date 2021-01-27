<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function users()
    {
        $groups = auth()->user()->groups;

        $users = User::where('id', '<>', auth()->user()->id)->get();
        $user = auth()->user();

        return 'all users';
    }
}
