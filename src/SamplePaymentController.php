<?php

/**
 * This is an example PaymentController.
 * Create your own controller based from this file.
 *
 * This controller will be available when APP_ENV=local
 */

// CHANGE HERE
// Change this namespace `App\Http\Controllers` when implementing in main app
namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;

class SamplePaymentController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = config('larapaymongo');
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
            'statement_descriptor' => $this->config['statement_descriptor'],
            'currency' => 'PHP',  // PayMongo only support PHP at the moment
            'metadata' => [
                'reference_id' => $id
            ],
        ]);
        
        return view('larapaymongo::samplepayment', [ 
            'name' => $item['name'],
            'description' => $item['description'],
            'currency' => $item['currency'],
            'price' => strval(number_format($item['price'], 2)),
            'client_key' => $paymentIntent->client_key,
        ]);
    }
}
