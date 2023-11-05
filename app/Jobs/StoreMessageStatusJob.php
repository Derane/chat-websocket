<?php

namespace App\Jobs;

use App\Events\StoreMessageEvent;
use App\Events\StoreMessageStatusEvent;
use App\Models\MessageStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreMessageStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $users;
    private $message;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $users, $message)
    {
        //
        $this->data = $data;
        $this->users = $users;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->users as $user) {
            MessageStatus::create([
                'user_id' => $user->id,
                'message_id' => $this->message->id,
                'chat_id' => $this->data['chat_id'],
            ]);
            $count = MessageStatus::where('chat_id', $this->data['chat_id'])
                ->where('user_id', auth()->id())->where('is_read', false)->count();
            broadcast(new StoreMessageStatusEvent($count, $this->data['chat_id'], $user->id, $this->message))->toOthers();
        }


        broadcast(new StoreMessageEvent($this->message))->toOthers();
    }
}
