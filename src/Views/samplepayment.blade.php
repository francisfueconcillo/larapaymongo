<?php /* 
    This is an example Purchase Page View. 
    Create your own view based from this file.

    This controller will be available in local enviroment only. APP_ENV=local
*/ ?> 

@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7 col-xs-11">

      <div class="card">
          <div class="card-header">Product Purchase</div>
          <div class="card-body">
              {{ $name }}<br/>
              {{ $description }}<br/>
              Status: {{ $status }}<br/><br/>
              Price: {{ $currency }} {{ $price }}
          </div>
      </div>   

      <br/>

      <div class="card">  
        <div class="card-header">Choose Payment Option</div>
        <div class="card-body">
          <ul role="tablist" class="nav bg-light nav-pills rounded-pill nav-fill mb-3">
              <li class="nav-item">
                <a data-toggle="pill" href="#nav-tab-card" class="nav-link active rounded-pill">
                  <i class="fa fa-credit-card"></i>
                  Credit/Debit Card
                </a>
              </li>
              
              <li class="nav-item">
                <a data-toggle="pill" href="#nav-tab-gcash" class="nav-link rounded-pill" >
                  GCash
                </a>
              </li>
              
              <li class="nav-item">
                <a data-toggle="pill" href="#nav-tab-grabpay" class="nav-link rounded-pill">
                  GrabPay
                </a>
              </li>
            </ul>
           
            <div class="tab-content">
              <larapay-card clientkey="{{ $client_key }}"></larapay-card>
              <larapay-gcash transactionid="{{ $id }}" price="{{ $price }}" currency="{{ $currency }}"></larapay-gcash>
              <larapay-grab transactionid="{{ $id }}" price="{{ $price }}" currency="{{ $currency }}"></larapay-grab>
            </div>
        </div>

      </div>
    </div>
  </div>

@endsection
