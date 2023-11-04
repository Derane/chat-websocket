<?php

namespace App\Events;

use App\Http\Resources\Message\MessageBroadcastResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoreMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('store-message.' . $this->message->chat_id),
        ];

    }

    public function broadcastAs(): string
    {
        return 'store-message';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => MessageBroadcastResource::make($this->message)->resolve(),
        ];
    }
}
