<?php /* 
    This is an example Payment Success page view. 
    Create your own view based from this file.

    This controller will be available in local enviroment only. APP_ENV=local
*/ ?> 

@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7 col-xs-11">

      <div class="card">
          <div class="card-header">Payment is done.</div>
          <div class="card-body">
              {{ $name }}<br/>
              {{ $description }}<br/>
              Status: {{ $status }}<br/><br/>
              Price: {{ $currency }} {{ $price }}
          </div>
      </div>   

      <br/>

    </div>
  </div>

@endsection
