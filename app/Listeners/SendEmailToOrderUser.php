<?php

namespace App\Listeners;

use App\Events\OrderPost;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPost as MailPost;

class SendEmailToOrderUser
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderPost  $event
     * @return void
     */
    public function handle(OrderPost $event): void
    {
        $event->order->express_type=$event->express_type;
        $event->order->express_no=$event->express_no;
        $event->order->status=3;//发货状态
        $event->order->save();
        //发货之后,邮件提醒
        Mail::to($event->order->user)->queue(new MailPost($event->order));
    }
}
