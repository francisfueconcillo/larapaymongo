<?php

namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use App\LaraPaymongoIntegrator;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;
use PepperTech\LaraPaymongo\Exceptions\InvalidParameterException;

class PaymentController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = config('larapaymongo');
    }

    /**
     * @param  String $referId Transaction Reference ID. examples: Order ID or Item ID
     *
     * @return View Payment Page view
     */
    public function index($referId)
    {
        $transaction = LaraPaymongoIntegrator::getTransactionDetails($referId);

        if ($transaction['name'] === null || $transaction['price'] === null || 
            !in_array($transaction['status'], ['paid', 'unpaid'])) {
            throw new InvalidParameterException('Invalid or missing parameters.');
        }

        if ($transaction['status'] === 'paid') {
            return view('larapaymongo::paymentsuccess', [ 
                'id' => $transaction['id'],
                'name' => $transaction['name'],
                'description' => $transaction['description'],
                'currency' => $transaction['currency'],
                'price' => strval(number_format($transaction['price'], 2)),
                'status' => strtoupper($transaction['status']),
            ]);
        } else {

            $paymentIntent = Paymongo::paymentIntent()->create([
                'amount' => number_format($transaction['price'], 2),  // Amount in cents. https://developers.paymongo.com/reference#create-a-paymentintent
                'payment_method_allowed' => [
                    'card'
                ],
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'automatic'
                    ]
                ],
                'description' => $transaction['name'],
                'statement_descriptor' => $this->config['statement_descriptor'],
                'currency' => 'PHP',  // PayMongo only support PHP at the moment
                'metadata' => [
                    'reference_id' => $referId
                ],
            ]);
            
            return view('larapaymongo::payment', [ 
                'id' => $transaction['id'],
                'name' => $transaction['name'],
                'description' => $transaction['description'],
                'currency' => $transaction['currency'],
                'price' => strval(number_format($transaction['price'], 2)),
                'status' => strtoupper($transaction['status']),
                'client_key' => $paymentIntent->client_key,
            ]);
        }

        
    }
}
