<?php

use Illuminate\Support\Facades\Route;

Route::get('/samplepurchase/{id}', 'PepperTech\LaraPaymongo\SamplePurchaseController@index')->name('samplepurchase');

Route::prefix('api')->group(function () {
  Route::get('samplepaymentcallback/{id}', 'PepperTech\LaraPaymongo\SamplePaymentCallbackController@index')->name('samplepaymentcallback');
});
