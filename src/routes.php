<?php

use Illuminate\Support\Facades\Route;

Route::get('/samplepurchase/{id}', 'PepperTech\LaraPaymongo\SamplePurchaseController@index')->name('samplepurchase');
