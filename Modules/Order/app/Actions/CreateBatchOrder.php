<?php

namespace Modules\Order\Actions;

use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Modules\Order\Http\Requests\BatchOrderRequest;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Jobs\ProcessOrder;
use Throwable;

class CreateBatchOrder extends BaseOrderAction
{
    public function __invoke(BatchOrderRequest $batchOrderRequest): void
    {
        // Here i created a chain with single batch for each ProcessOrder.
        Bus::chain(array_map(
            function ($order) use ($batchOrderRequest) {
                $orderRequest = (new OrderRequest($order));
                $orderRequest->merge(['user_id' => $batchOrderRequest->user()->id]);
                return
                    Bus::batch([
                        new ProcessOrder($orderRequest),
                    ]);
            },
            $batchOrderRequest->input('orders')
        ))->dispatch();
    }
}
