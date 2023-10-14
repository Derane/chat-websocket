<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreRequest;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\User\UserResource;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {

        $users = User::where('id', '!=', auth()->id())->get();
        $chats = auth()->user()->chats()->has('messages')->get();
        $chats = ChatResource::collection($chats)->resolve();
        $users = UserResource::collection($users);
        return inertia('Chat/Index', compact('chats', 'users'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $users = array_merge($data['users'], [auth()->id()]);
        sort($users);
        $usersIds = $users;
        $users = implode('-', $users);
        try {
            DB::beginTransaction();
            $chat = Chat::firstOrCreate(['users' => $users], ['title' => $data['title']]);
            $chat->users()->sync($usersIds);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        $chat = ChatResource::make($chat)->resolve();
        return inertia('Chat/Show', compact('chat'));
    }

    public function show(Chat $chat)
    {
        $chat = ChatResource::make($chat)->resolve();
        return inertia('Chat/Show', compact('chat'));
    }
}
