<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPost
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public string $express_type,$express_no;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order,string $express_type,string $express_no)
    {
        $this->order=$order;
        $this->express_type=$express_type;
        $this->express_no=$express_no;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        return new PrivateChannel('channel-name');
    }
}
