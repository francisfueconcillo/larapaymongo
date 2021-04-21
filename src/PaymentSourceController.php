<?php

namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use App\LaraPaymongoIntegrator;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;
use PepperTech\LaraPaymongo\Exceptions\InvalidParameterException;

class PaymentSourceController extends Controller
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

        $transaction = LaraPaymongoIntegrator::getTransactionDetails($referId);
        $sourceId = $transaction['source_id'];
        
        if ($sourceId !== null) {
            $currentSource = Paymongo::source()->find($sourceId);
            $sourceAttr = $currentSource->getAttributes();
            
            if ($currentSource->status === 'pending') {
                return json_encode([
                    'code' => 'reuse',
                    'id' => $currentSource->id,
                    'checkout_url' => $sourceAttr['redirect']['checkout_url'],
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
            } else if($currentSource->status === 'paid') {
                return json_encode([
                    'code' => 'paid',
                ]);
            }

        } 

        // If Source is not existing or expired, need to create a new one
        $source = Paymongo::source()->create([
            'type' => $method,
            'amount' => $transaction['price'],
            'currency' => 'PHP',
            'redirect' => [
                'success' => config('app.url').'/payment/details/'.$referId,
                'failed' => config('app.url').'/payment/details/'.$referId,
            ]
        ]);
        $sourceAttr = $source->getAttributes();

        LaraPaymongoIntegrator::updateTransactionSourceId($referId, $sourceAttr['id']);
        
        return json_encode([
            'code' => 'new',
            'id' => $sourceAttr['id'],
            'checkout_url' => $sourceAttr['redirect']['checkout_url'],
        ]);
       
    }
}
