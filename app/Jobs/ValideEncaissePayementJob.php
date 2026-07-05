<?php

namespace App\Jobs;

use App\Events\OrderState;
use App\Models\Order;
use App\Models\Payement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Payements\PayementModeInterface;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ValideEncaissePayementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected PayementModeInterface $payement_mode_interface,
        protected Payement $payement,
        protected Order $order,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {       
        $this->payement_mode_interface->moneyTransaction($this->payement);
        OrderState::dispatch($this->order);

    }
}
