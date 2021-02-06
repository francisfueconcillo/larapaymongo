<?php

use Illuminate\Support\Facades\Route;

Route::get('/payment/{referId}', 'PepperTech\LaraPaymongo\PaymentController@index');
Route::get('/payment/source/{method}/{referId}', 'PepperTech\LaraPaymongo\PaymentSourceController@index');
Route::get('/payment/verify/{paymentIntentId}', 'PepperTech\LaraPaymongo\PaymentVerifyController@index');
Route::get('/payment/callback/{referId}', 'PepperTech\LaraPaymongo\PaymentCallbackController@index');