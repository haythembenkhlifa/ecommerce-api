<?php

namespace Modules\Order\Enums;

enum PaymentMethods: string
{
    case CARD = 'card';
    case COD = 'cod';
}
