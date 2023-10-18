<?php

namespace App\Http\Controllers;

use App\Events\StoreMessageEvent;
use App\Events\StoreMessageStatusEvent;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\MessageStatus;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function store(StoreRequest $request)
    {

        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $message = auth()->user()->messages()->create($data);
            $users = auth()->user()->chats()->findOrFail($data['chat_id'])->users()
                ->where('users.id', '!=', auth()->id())->get();
            foreach ($users as $user) {
                MessageStatus::create([
                    'user_id' => $user->id,
                    'message_id' => $message->id,
                    'chat_id' => $data['chat_id'],
                ]);
                $count = MessageStatus::where('chat_id', $data['chat_id'])
                    ->where('user_id', auth()->id())->where('is_read', false)->count();
                broadcast(new StoreMessageStatusEvent($count))->toOthers();
            }


            broadcast(new StoreMessageEvent($message))->toOthers();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
        return MessageResource::make($message)->resolve();
    }
}
