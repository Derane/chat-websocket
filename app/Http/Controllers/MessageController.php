<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Jobs\StoreMessageStatusJob;

class MessageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $message = auth()->user()->messages()->create($data);
        $users = auth()->user()->chats()->findOrFail($data['chat_id'])->users()
            ->where('users.id', '!=', auth()->id())->get();

        StoreMessageStatusJob::dispatch($data, $users, $message)->onQueue('store_message');

        return MessageResource::make($message)->resolve();
    }


}
