<?php

namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use App\LaraPaymongoIntegrator;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;
use PepperTech\LaraPaymongo\Exceptions\InvalidParameterException;
use PepperTech\LaraPaymongo\Exceptions\FatalErrorException;

class PaymentDetailsController extends Controller
{
    private $config;
    
    public function __construct()
    {
        $this->config = config('larapaymongo');
    }


    /**
     *
     * @param  Illuminate\Http\Request $request
     * @param  String $referId Application's transaction Reference ID
     *
     * @return JSON JSON object that contains success information or error.
     */
    public function index(Request $request, $referId)
    {
        if ($referId === null) {
            throw new InvalidParameterException('Reference ID is missing');
        }

        $transaction = LaraPaymongoIntegrator::getTransactionDetails($referId);

        if ($transaction['source_id'] === null) {
            throw new FatalErrorException('Source ID is expected but is not found.');
        }

        if ($transaction['status'] === 'unpaid') {
            $source = Paymongo::source()->find($transaction['source_id']);

            if ($source->status == 'chargeable') {
                $payment = Paymongo::payment()->create([
                    'amount' => $source->amount,
                    'currency' => $source->currency,
                    'description' => ucfirst($source->type).' Payment - Ref# '.$referId,
                    'statement_descriptor' => $this->config['statement_descriptor'],
                    'source' => [
                        'id' => $source->id,
                        'type' => 'source'
                    ]
                ]);

                if ($payment->status === 'paid') {
                    LaraPaymongoIntegrator::completeTransaction($referId);
                    $transaction['status'] = 'paid';
                    return view('larapaymongo::paymentsuccess', $transaction);
                }
            }

            // payment failed scenarios -  ask user to enter payment details again
            $paymentIntent = Paymongo::paymentIntent()->create([
                'amount' => $transaction['price'],  // Amount in cents. https://developers.paymongo.com/reference#create-a-paymentintent
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

            return view('larapaymongo::paymentfail', [ 
                'id' => $transaction['id'],
                'name' => $transaction['name'],
                'description' => $transaction['description'],
                'currency' => $transaction['currency'],
                'price' => strval(number_format($transaction['price'], 2)),
                'status' => strtoupper($transaction['status']),
                'client_key' => $paymentIntent->client_key,
            ]); 
        }

        $transaction['status'] = strtoupper($transaction['status']);
        return view('larapaymongo::paymentsuccess', $transaction);
    }


    

}
