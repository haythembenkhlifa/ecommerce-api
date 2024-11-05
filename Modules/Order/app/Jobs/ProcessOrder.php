<?php

namespace Modules\Order\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Actions\CreateOrder;
use Modules\Order\Http\Requests\OrderRequest;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(public OrderRequest $orderRequest) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new CreateOrder)($this->orderRequest);
    }
}
