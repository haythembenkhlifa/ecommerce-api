<?php

namespace Modules\Order\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;

class BaseModel extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->setShardForWrite();
        });

        static::retrieving(function ($model) {
            $model->setShardForRead();
        });
    }

    protected function setShardForWrite()
    {
        // Determine the shard based on the key (user ID, product ID, etc.)
        if ($this instanceof Order) {
            $this->setConnection($this->getOrderShard($this->id));
        } elseif ($this instanceof OrderLog) {
            $this->setConnection($this->getOrderLogShard($this->id));
        } elseif ($this instanceof OrderPayment) {
            $this->setConnection($this->getOrderPaymentShard($this->user_id));
        } elseif ($this instanceof OrderProduct) {
            $this->setConnection($this->getOrderProductShard($this->user_id));
        }
    }

    protected function setShardForRead()
    {
        // Similar logic to determine the shard for reading
        if ($this instanceof User) {
            $this->setConnection($this->getUserShard($this->id));
        } elseif ($this instanceof Product) {
            $this->setConnection($this->getProductShard($this->id));
        } elseif ($this instanceof Order) {
            $this->setConnection($this->getOrderShard($this->user_id));
        }
    }

    protected function getUserShard($userId)
{
    return $userId % 2 === 0 ? 'users_shard_2' : 'users_shard_1';
}

protected function getProductShard($productId)
{
    return $productId % 2 === 0 ? 'products_shard_2' : 'products_shard_1';
}

protected function getOrderShard($userId)
{
    return $userId % 2 === 0 ? 'orders_shard_2' : 'orders_shard_1';
}
}
