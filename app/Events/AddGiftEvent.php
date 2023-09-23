<?php

namespace App\Events;

use App\Http\Resources\GiftResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddGiftEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gift;
    public $user_id;
    public function __construct($user_id,$gift)
    {
        $this->gift = $gift;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            'gift-channel'. $this->user_id
        ];
    }

    public function broadcastAs() {
        return 'add-gift';
    }

    public function broadcastWith(){
        return [
            'gift' => GiftResource::make($this->gift)
        ];
    }


}
