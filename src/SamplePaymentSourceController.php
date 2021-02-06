<?php

/**
 * This is an example PaymentSourceController.
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

class SamplePaymentSourceController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = config('larapaymongo');
    }


    /**
     * @param  String $method Payment Source type. gcash|grab_pay (Required)
     * @param  String $referId Application's transaction Reference ID (Required)
     *
     * @return JSON JSON object that contains success flag
     */
    public function index($method, $referId)
    {

        if ($referId === null ||
            ! in_array($method, [ 'gcash', 'grab_pay' ])) {
            throw new InvalidParameterException('Invalid parameters.');
        }

        $transation = LaraPaymongoIntegrator::getTransactionDetails($referId);
        $sourceId = $transation['source_id'];
        
        if ($sourceId !== null) {
            $currentSource = Paymongo::source()->find($sourceId);
            
            if ($currentSource->status === 'pending') {
                return json_encode([
                    'code' => 'reuse',
                    'id' => $currentSource->id,
                    'checkout_url' => $currentSource->attributes->redirect->checkout_url,
                ]);
            } else if ($currentSource->status === 'chargeable') {
                // should create payment now

                $payment = Paymongo::payment()->create([
                    'amount' => $currentSource->amount,
                    'currency' => $currentSource->currency,
                    'description' => ucfirst($currentSource->type).' Payment - Ref# '.$referId,
                    'statement_descriptor' => $this->config['statement_descriptor'],
                    'source' => [
                        'id' => $referId,
                        'type' => 'source'
                    ]
                ]);

                if ($payment->status === 'paid') {
                    LaraPaymongoIntegrator::completeTransaction($referId);
                    return json_encode([
                        'code' => 'paid',
                    ]);
                }
            }

        } 

        // If Source is not existing or expired, need to create a new one
        $source = Paymongo::source()->create([
            'type' => $method,
            'amount' => number_format($transaction['price'], 2),
            'currency' => 'PHP',
            'redirect' => [
                'success' => config('app')['url'].$this->config['callback_url'].'/success/'.$referId,
                'failed' => config('app')['url'].$this->config['callback_url'].'/fail/'.$referId,
            ]
        ]);

        LaraPaymongoIntegrator::updateTransactionSourceId($referId, $source->id);
        
        return json_encode([
            'code' => 'new',
            'id' => $source->id,
            'checkout_url' => $source->attributes->redirect->checkout_url,
        ]);
       
    }
}
