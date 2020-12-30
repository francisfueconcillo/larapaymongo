<?php

// Change this namespace `App\Http\Controllers` when implementing in main app
namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;

/* 
    This is an example Purchase Page Controller. 
    Create your own controller based from this file.

    This controller will be available in local enviroment only. APP_ENV=local
    
*/

class SamplePurchaseController extends Controller
{
    
    public function __construct()
    {
        // it is recommended to secure this page with authentication
        // $this->middleware('auth');  
    }

    public function index($id)
    {
        /*  
            $item should be queried from database based on the passed parameter $id.
            $id can also be an Order ID, in case of multiple items in an order 
            in most ecommerce implementations.
        */

        $item = [
            'id' => $id,
            'name' => 'Sample Product '.$id,
            'description' => 'A very cool product',
            'price' => 100,
            'currency' => 'PHP',
        ];

        $itemShortDesc = strtr('(@id) @name', [ 
            '@id' => $item['id'], 
            '@name' => $item['name'], 
        ]);

        $paymentIntent = Paymongo::paymentIntent()->create([
            'amount' => number_format($item['price'], 2),  // Amount in cents. https://developers.paymongo.com/reference#create-a-paymentintent
            'payment_method_allowed' => [
                'card'
            ],
            'payment_method_options' => [
                'card' => [
                    'request_three_d_secure' => 'automatic'
                ]
            ],
            'description' => $itemShortDesc,
            'statement_descriptor' => env('PAYMONGO_STATEMENT_DESCRIPTOR', 'LaraPaymongo'),
            'currency' => $item['currency'],  // PayMongo only support PHP at the moment
        ]);
        
        return view('larapaymongo::samplepurchase', [ 
            'name' => $item['name'],
            'description' => $item['description'],
            'currency' => $item['currency'],
            'price' => strval(number_format($item['price'], 2)),
            'client_key' => $paymentIntent->client_key,
        ]);
    }
}
