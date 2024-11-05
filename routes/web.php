<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Order\Events\OrderConfirmedEvent;
use Modules\Order\Models\Order;

Route::get('/', function () {
    return view('welcome');
});
