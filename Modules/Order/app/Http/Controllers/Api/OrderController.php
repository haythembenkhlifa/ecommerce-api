<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Order\Actions\CreateBatchOrder;
use Modules\Order\Actions\CreateOrder;
use Modules\Order\Actions\DeleteOrder;
use Modules\Order\Actions\MarkOrderAsPaid;
use Modules\Order\Actions\UpdateOrder;
use Modules\Order\Events\ProcessPaymentEvent;
use Modules\Order\Http\Requests\BatchOrderRequest;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Transformers\OrderResource;
use Modules\User\Models\User;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $this->authorize('viewAny', Order::class);

        return OrderResource::collection(
            Order::paginate(
                config('order.orders_per_page', 100)
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $orderRequest)
    {
        return new OrderResource((new CreateOrder)($orderRequest));
    }

    /**
     * Store a a batch of orders.
     */
    public function batchStore(BatchOrderRequest $batchOrderRequest)
    {
        return (new CreateBatchOrder)($batchOrderRequest);
    }


    /**
     * Show the specified resource.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        OrderRequest $orderRequest,
        Order $order
    ) {
        return new OrderResource((new UpdateOrder)($orderRequest, $order));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        (new DeleteOrder)($order);
    }

    /**
     * Mark order as paid.
     */
    public function markOrderAsPayed(Order $order)
    {
        ProcessPaymentEvent::dispatch($order);
    }
}
