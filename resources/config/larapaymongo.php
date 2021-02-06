<?php 

return [
  'statement_descriptor' => env('PAYMONGO_STATEMENT_DESCRIPTOR', null),
  
  'secret_key' => env('PAYMONGO_SECRET_KEY', null),
  
  'public_key' => env('PAYMONGO_PUBLIC_KEY', null),
  
  'webhook_sig' => env('PAYMONGO_WEBHOOK_SIG', null),

  'callback_url' => env('MIX_PAYMENT_SOURCE_CALLBACK_URL', '/payment/callback'),
];