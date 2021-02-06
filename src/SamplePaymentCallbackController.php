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
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;
use PepperTech\LaraPaymongo\Exceptions\InvalidParameterException;

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
     * @param  String $result Payment result flag. success|fail
     * @param  String $id The application's Reference ID for the transaction (not Paymongo's)
     * @return JSON JSON object that contains success information or error.
     */
    public function index(Request $request, $result, $id)
    {
        if (! in_array($result, [ 'success', 'fail' ])) {
            throw new InvalidParameterException('Invalid result string');
        }

        if ($id === null) {
            throw new InvalidParameterException('Reference ID is missing');
        }

        // TODO - query for source id based from reference id
        $sourceId = '111';

        $failView = view('larapaymongo::samplepaymentfail', [ 
            'id' => $id,
        ]);

        $successView = view('larapaymongo::samplepaymentsuccess', [ 
            'id' => $id,
        ]);


        if ($request === 'success') {
            $source = Paymongo::source()->find($sourceId);

            if ($source->status == 'chargeable') {

                $payment = Paymongo::payment()->create([
                    'amount' => $source->amount,
                    'currency' => $source->currency,
                    'description' => 'Payment thru '.ucfirst($source->type).': '.$id,
                    'statement_descriptor' => $this->config['statement_descriptor'],
                    'source' => [
                        'id' => $id,
                        'type' => 'source'
                    ]
                ]);

                if ($payment->status === 'paid') {
                    // CHANGE HERE
                    // The gcash or grab_pay payment was successful.
                    // Further actions might be needed, like closing an order
                    // Query the Source ID $id from your database to reference. 
                    // Source ID must be stored in database during creation of Paymongo Source.

                    return $successView;
                } else {
                    return $failView;
                }

               

            } else if ($source->status == 'expired') {
                return $failView;
            }

            return $failView;


        } else {
            return $failView;
        }


        

        
    }

}
