<?php

/**
 * Verifies card payments and allows execution of other backend tasks to complete the transaction.
 *
 * This is an example for PaymentVerifyController. This will only be available when APP_ENV=local
 */


// CHANGE HERE
// Change this namespace `App\Http\Controllers` when implementing in main app
namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use App\LaraPaymongoIntegrator;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;
use PepperTech\LaraPaymongo\Exceptions\InvalidParameterException;

class SamplePaymentVerifyController extends Controller
{
    private $config;
    
    public function __construct()
    {
        $this->config = config('larapaymongo');
    }

    /**
     * @param  String $paymentIntentId Payment Intent ID
     *
     * @return JSON JSON object that contains success flag
     */
    public function index($paymentIntentId)
    {
        if ($paymentIntentId === null) {
            throw new InvalidParameterException('Payment Intent ID is missing');
        }

        $paymentIntent = Paymongo::paymentIntent()->find($paymentIntentId);
        
        // reference_id is the ID passed in the metadata when Payment Intent was created.
        $referId = $paymentIntent->metadata['reference_id'];
        
        if ($paymentIntent->status == 'succeeded') {
            LaraPaymongoIntegrator::completeTransaction($referId);
        }

        $resp = [
            'success' => ($paymentIntent->status == 'succeeded') ? true : false
        ];

        return json_encode($resp);
    }

}
