<?php

namespace Modules\Order\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELED = 'canceled';
    case SHIPPED = 'shipped';
    case REFUNDED = 'refunded';
}
