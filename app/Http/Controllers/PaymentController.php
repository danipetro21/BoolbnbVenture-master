<?php

namespace App\Http\Controllers;

use Braintree\Gateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function getToken()
    {
        $gateway = new Gateway([
            'environment' => env('BRAINTREE_ENVIRONMENT'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        $clientToken = $gateway->clientToken()->generate();
        return response()->json(['token' => $clientToken]);
    }

    public function createTransaction(Request $request)
    {
        $gateway = new Gateway([
            'environment' => env('BRAINTREE_ENVIRONMENT'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        $nonce = $request->input('payment_method_nonce');
        $amount = $request->input('amount');
        $result = $gateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);
        if ($result->success) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => $result->message]);
        }
    }
}
