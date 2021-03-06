<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Mail\SendMailreset;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{
    public function sendEmail(Request $request)  // this is most important function to send mail and inside of that there are another function
    {
        if (!$this->validateEmail($request->email)) {  // this is validate to fail send mail or true
            return $this->failedResponse();
        }
        $this->send($request->email);  //this is a function to send mail
        return $this->successResponse();
    }

    public function send($email)  //this is a function to send mail
    {
        $token = $this->createToken($email);//var_dump($token);
        Mail::to($email)->send(new SendMailreset($token, $email));  // token is important in send mail
    }

    public function createToken($email)  // this is a function to get your request email that there are or not to send mail
    {
        $user = DB::table('users')->where('email', $email)->first();
        if($user) {
            $coordonnees = DB::table('coordonnees_authentifications')->where('user_id', $user->id)->first();
            if ($coordonnees) {
                //$oldToken = DB::table('password_resets')->where('email', $email)->first();
                $token = Str::random(40);
                $this->saveToken($token,$user->id);
                return $coordonnees->token;
            }
        }
    }


    public function saveToken($token,$user_id)  // this function save new password
    {
        DB::table('coordonnees_authentifications')->where('user_id', $user_id)->update([
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }



    public function validateEmail($email)  //this is a function to get your email from database
    {
        return !!User::where('email', $email)->first();
    }

    public function failedResponse()
    {
        return response()->json([
            'error' => 'Email doesn\'t exist'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse()
    {
        return response()->json([
            'data' => 'Reset Email is sent successfully, please check your inbox.'
        ], Response::HTTP_OK);
    }
}
