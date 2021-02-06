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
     * @param  Illuminate\Http\Request $request
     * @param  String $id Payment Intent ID
     * @return JSON JSON object that contains success flag
     */
    public function index(Request $request, $id)
    {
        if ($id === null) {
            throw new InvalidParameterException('Payment Intent ID is missing');
        }

        $paymentIntent = Paymongo::paymentIntent()->find($id);
        
        // $referenceId is the ID passed in the metadate Payment Intent was created.
        $referenceId = $paymentIntent->metadata['reference_id'];
        
        if ($paymentIntent->status == 'succeeded') {
            // CHANGE HERE
            // The card payment was successfull
            // Further actions might be needed, like closing an order
            // Use the $referenceId as reference to this transaction
        }

        $resp = [
            'success' => ($paymentIntent->status == 'succeeded') ? true : false
        ];

        return json_encode($resp);
    }

}
