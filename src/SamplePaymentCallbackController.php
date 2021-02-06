<?php

/**
 * This is an example PaymentCallbackController.
 * Create your own controller based from this file.
 *
 * This controller will be available when APP_ENV=local
 */


// CHANGE HERE
// Change this namespace `App\Http\Controllers` when implementing in main app
namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use App\LaraPaymongoIntegrator;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;
use PepperTech\LaraPaymongo\Exceptions\InvalidParameterException;
use PepperTech\LaraPaymongo\Exceptions\FatalErrorException;

class SamplePaymentCallbackController extends Controller
{
    private $config;
    
    public function __construct()
    {
        $this->config = config('larapaymongo');
    }


    /**
     * Sample Controller for samplepaymentcallback/{method}/{id} route.
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

        $failView = view('larapaymongo::samplepaymentfail', $transaction);
        $successView = view('larapaymongo::samplepaymentsuccess', $transaction);

        if ($transaction['status'] === 'unpaid') {
            $source = Paymongo::source()->find($transaction['source_id']);

            if ($source->status == 'chargeable') {
                $payment = Paymongo::payment()->create([
                    'amount' => $source->amount,
                    'currency' => $source->currency,
                    'description' => ucfirst($source->type).' Payment - Ref# '.$referId,
                    'statement_descriptor' => $this->config['statement_descriptor'],
                    'source' => [
                        'id' => $referId,
                        'type' => 'source'
                    ]
                ]);

                if ($payment->status === 'paid') {
                    LaraPaymongoIntegrator::completeTransaction($referId);
                    return $successView;
                } else {
                    return $failView;
                }

            } else if ($source->status == 'expired') {
                return $failView;
            }

            return $failView;

        } else {
            return $successView;
        }


        

        
    }

}
