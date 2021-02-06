<?php

use Illuminate\Support\Facades\Route;

Route::get('/sample/payment/{id}', 'PepperTech\LaraPaymongo\SamplePaymentController@index');
Route::get('/sample/payment/source/{method}/{id}', 'PepperTech\LaraPaymongo\SamplePaymentSourceController@index');
Route::get('/sample/payment/verify/{id}', 'PepperTech\LaraPaymongo\SamplePaymentVerifyController@index');
Route::get('/sample/payment/callback/{result}/{id}', 'PepperTech\LaraPaymongo\SamplePaymentCallbackController@index');


Route::get('/sample/payment/callback/{method}/{id}', 'PepperTech\LaraPaymongo\SamplePaymentCallbackController@index');
