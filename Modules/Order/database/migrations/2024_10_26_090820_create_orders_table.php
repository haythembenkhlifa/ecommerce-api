<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Enums\PaymentMethods;

return new class extends Migration
{
    // for now will use to shard for each table for testing.
    public $nbSards = 2;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->createOrdersShards();
    }

    public function createOrdersShards()
    {
        // for ($shard = 1; $shard = $this->nbSards; $shard++) {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('payment_method')->default(PaymentMethods::COD->value);
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default(OrderStatus::PENDING->value);
            // The address details should be in separate table but just for testing i will keep is here.
            $table->string('shipping_address_line_1');
            $table->string('shipping_address_line_2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_postal_code');
            $table->string('billing_address_line_1')->nullable();
            $table->string('billing_address_line_2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });

        // Payment in real world "should" be a separate module but for now i will keep all here to save sometime.
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default(PaymentMethods::COD->value);
            $table->string('status')->default(OrderStatus::PENDING->value);
            $table->string('payment_gateway')->nullable()->default('stripe');
            $table->string('transaction_id')->nullable();
            $table->text('transaction_payload')->nullable();
            $table->timestamps();
        });

        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('context');
            $table->string('content');
            $table->timestamps();
        });
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('order_logs');
        // for ($shard = 1; $shard = $this->nbSards; $shard++) {

        //     Schema::dropIfExists('orders_shard_' . $shard);
        //     Schema::dropIfExists('order_product_shard_' . $shard);
        //     Schema::dropIfExists('order_payments_shard_' . $shard);
        //     Schema::dropIfExists('order_logs_shard_' . $shard);
        // }
    }
};
