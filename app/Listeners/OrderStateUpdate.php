<?php

namespace App\Listeners;

use App\Events\OrderState;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderStateUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderState $event): void
    {
        //
        $order = $event->order;
        $order->status = 'shipped';
        $order->save();

    }
}
