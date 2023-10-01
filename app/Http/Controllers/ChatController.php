<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {

        $users = User::all();

        $users = UserResource::collection($users);
        return inertia('Chat/Index', compact('users'));
    }
}
