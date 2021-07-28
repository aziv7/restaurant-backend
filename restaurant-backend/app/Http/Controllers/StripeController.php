<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

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

        
      $pay= $stripe->charges->create([
            'amount' => $request->prixtot*100,
            'currency' => 'eur',
            'source' => $request->token,
            'description' => 'payment',

      ]);
    return   $response = [ 'checkout'=>$pay,
        'prixtotal'=> $request->prixtot,
        'cart'=>$request->card,
        'id_code_reduction'=>$request->idCodRed,
        'address'=>$request->address,
        'longitude'=>$request->longitude,
        'latitude'=>$request->latitude,
        'code_reduction'=>$request->codered,
        'date_payment'=>Carbon::now()
    ];
      
    }

    public function charges(Request $request){
        $stripe = new \Stripe\StripeClient(
            'sk_test_51J9zB2EQevdhZyUKTOZeSfMyd57956WAdKdnUIAS59wkTw7yPXzavY18a92czBGuqNzfXDANAZNRsFcX81jdP04p00t5heW0dE'
        );

        return  $stripe->charges->all();
    }
}
