<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreRequest;
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

    public function store(StoreRequest $request)
    {
      dd($request->all());
    }
}
