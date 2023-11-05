<?php

namespace App\Events;

use App\Http\Resources\Message\MessageResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class   StoreMessageStatusEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    private $count;
    private $chatId;
    private $userId;

    /**
     * Create a new event instance.
     */
    public function __construct($count, $chatId, $userId, $message)
    {
        $this->count = $count;
        $this->chatId = $chatId;
        $this->userId = $userId;
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
            new PrivateChannel('users.' . $this->userId),
        ];

    }

    public function broadcastAs(): string
    {
        return 'store-message-status';
    }

    public function broadcastWith(): array
    {
        return [
            'chat_id' => $this->chatId,
            'count' => $this->count,
            'message' => MessageResource::make($this->message)->resolve(),

        ];
    }
}
