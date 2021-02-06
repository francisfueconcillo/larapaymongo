<?php

use Illuminate\Support\Facades\Route;

Route::get('/sample/payment/{referId}', 'PepperTech\LaraPaymongo\SamplePaymentController@index');
Route::get('/sample/payment/source/{method}/{referId}', 'PepperTech\LaraPaymongo\SamplePaymentSourceController@index');
Route::get('/sample/payment/verify/{paymentIntentId}', 'PepperTech\LaraPaymongo\SamplePaymentVerifyController@index');
Route::get('/sample/payment/callback/{referId}', 'PepperTech\LaraPaymongo\SamplePaymentCallbackController@index');