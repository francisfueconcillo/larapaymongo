<?php

// Change this namespace `App\Http\Controllers` when implementing in main app
namespace PepperTech\LaraPaymongo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Luigel\Paymongo\Facades\Paymongo;

class SamplePaymentCallbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request, $id)
    {
        $paymentIntent = Paymongo::paymentIntent()->find($id);
        
        // Item ID related to Payment Intent was passed during creation, so we ca reference it here
        $metadata = $paymentIntent->metadata;
        $itemid = $metadata['itemid'];   

        if ($paymentIntent->status == 'succeeded') {
            // IMPLEMENT THE FOLLOWING, based on your application behavior.
            // Examples: 
            // - Order is closed and/or payment confirmation is sent to user by email
            // - Decrement item in the inventory
        }

        $resp = [
            'success' => ($paymentIntent->status == 'succeeded') ? true : false
        ];

        return json_encode($resp);
    }
}
