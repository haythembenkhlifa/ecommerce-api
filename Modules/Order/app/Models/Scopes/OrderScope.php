<?php

namespace Modules\Order\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()?->user()) {
            if (!auth()?->user()?->hasRole(['admin', 'order-manager']))
                $builder->where('user_id', auth()?->user()?->id);
        }
    }
}
