<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function getbananas()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        return $stripe->balance->retrieve();

    }

    public function payments(Request $request){
        $stripe = new \Stripe\StripeClient(
            'sk_test_51J9zB2EQevdhZyUKTOZeSfMyd57956WAdKdnUIAS59wkTw7yPXzavY18a92czBGuqNzfXDANAZNRsFcX81jdP04p00t5heW0dE'
        );

       return  $stripe->charges->create([
            'amount' => 2000,
            'currency' => 'eur',
            'source' => $request->token,
            'description' => 'payment',
        ]);
    }
}
