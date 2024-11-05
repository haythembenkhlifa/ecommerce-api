<?php

namespace Modules\Order\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case Failed = 'failed';
}
