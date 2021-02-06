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

    public function index($method, $id)
    {

        if (! in_array($method, [ 'gcash', 'grab_pay' ])) {
            throw new InvalidParameterException('Invalid method '.$method);
        }

        $source = Paymongo::source()->create([
            'type' => $method,
            'amount' => 100.00,
            'currency' => 'PHP',
            'redirect' => [
                'success' => 'https://your-domain.com/success',
                'failed' => 'https://your-domain.com/failed'
            ]
        ]);
        
       
    }
}
