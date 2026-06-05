<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


        public Message $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        $receiverid = $this->getReceiverid();
        return [
            new PrivateChannel('notifications.' . $receiverid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new.message';
    }

    public function broadcastWith(): array
    {
        $sender = $this->message->user;

        return [
            'message_id'      => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'user_id'         => $this->message->user_id,
            'sender_name'     => $sender ? trim("{$sender->first_name} {$sender->last_name}") : 'مستخدم',
            'message_text'    => $this->message->message_text,
            'send_date'       => $this->message->send_date,
            'file_path'       => $this->message->file_path,
            'file_name'       => $this->message->file_name,
        ];
    }

    public function getReceiverid():int
    {
        $conversation = $this->message->conversation;
        return $conversation->user_id_1 == $this->message->user_id
            ? $conversation->user_id_2
            : $conversation->user_id_1;
    }
}
