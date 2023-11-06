<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreRequest;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\User\UserResource;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $chats = auth()->user()
            ->chats()
            ->has('messages')
            ->with('lastMessage')
            ->withCount('unreadableMessageStatuses')
            ->get();
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
        return redirect()->route('chats.show', $chat->id);
    }

    public function show(Chat $chat)
    {
        $page = request('page') ?? 1;

        $users = $chat->users()->where('users.id', '!=', auth()->id())->get();

        $messages = $chat->messages()->with('user')
            ->orderByDesc('created_at')
            ->paginate(5, '*', 'page', $page);
        $messages = MessageResource::collection($messages)->resolve();
        $users = UserResource::collection($users)->resolve();
        $chat->unreadableMessageStatuses()->update(['is_read' => true]);
        $chat = ChatResource::make($chat)->resolve();


        return inertia('Chat/Show', compact('chat', 'users', 'messages'));
    }
}
