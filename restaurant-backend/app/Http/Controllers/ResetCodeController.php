<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;
 use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Mail\ResetCodeVerif;
use App\Models\ResetCode;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetCodeController extends Controller
{


    function randomCode($email)
    { $user = DB::table('users')->where('email', $email)->first();
      var_dump($user->id);
        if($user) {
        $numbers = '1234567890';
        $code= array(); //remember to declare $pass as an array
        $alphaLength = strlen($numbers) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $code[] = $numbers[$n];
        } 
        
        DB::insert('insert into reset_codes (code,user_id,  created_at) values (?,?,?)', [implode($code), $user->id,Carbon::now()]);

        
        return implode($code);
    }
    else
    return response(array(
        'message' => 'verify your email',
    ), 403);
        //turn the array into a string
    }
    public function sendEmail(Request $request)  // this is most important function to send mail and inside of that there are another function
    {
        if (!$this->validateEmail($request->email)) {  // this is validate to fail send mail or true
            return $this->failedResponse();
        }
        $this->send($request->email);  //this is a function to send mail
        return $this->successResponse();
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
    public function send($email)  //this is a function to send mail
    {
        $code = $this->randomCode($email);var_dump($code);
        Mail::to($email)->send(new ResetCodeVerif($code, $email));  // token is important in send mail
    }

    public function validateEmail($email)  //this is a function to get your email from database
    {
        return !!User::where('email', $email)->first();
    }


    public function verifExistanceCode($code){
        $code_reset = DB::table('reset_codes')->where('code', $code)->first();
if($code_reset)
return true;
else return false;
    }
    public function VerifCodeReset(request $request,$code){
        $code_reset = DB::table('reset_codes')->where('code', $code)->first();
    if($code_reset)
    { 
        $to =  Carbon::parse($code_reset->created_at);

        $from =  Carbon::parse( Carbon::now());
        
        $diff_in_minutes = $to->diffInMinutes($from,true);
        
        print_r($diff_in_minutes); // Output: 1
      var_dump($code_reset->created_at); // Output: 1
       print_r( Carbon::now()); // Output: 1

        if($diff_in_minutes>10)
        return response(array(
            'message' => 'Expired code',
        ), 403);
        else 
        $this->passwordResetProcess($request,$code);

    }
    else  return response(array(
        'message' => 'Invalid code',
    ), 403);
    }
    
        public function passwordResetProcess(request $request,$code){
            return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request,$code) : $this->tokenNotFoundError();
        }
    
        // Verify if code is valid
        private function updatePasswordRow($request){
            $user= DB::table('users')->where('email' , $request->email)->first();
            if($user) {
                    return  DB::table('coordonnees_authentifications')
                        ->where('user_id' , $user->id);
    
            }
        }
    
        // code not found response
        private function tokenNotFoundError() {
            return response()->json([
                'error' => 'Either your email or token is wrong.'
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    
        // Reset password
        private function resetPassword($request,$code) {
            // find email
            $userData = DB::table('users')->where('email', $request->email)->first();
            var_dump($userData);
            //finding coordonnees_authentification of this user
            if($userData) {
                $coordauth = DB::table('coordonnees_authentifications')->where('user_id', $userData->id)->first();
                var_dump($coordauth);
                if($coordauth) {
                    // update password and reset token
                    DB::table('coordonnees_authentifications')->where('user_id', $userData->id)->update([
                    
                        'password'=>Hash::make($request->password),
                    ]);
                    $code_reset=DB::table('reset_codes')->where('code',$code)->get()->first();
ResetCode::destroy($code_reset->id);
                    var_dump($coordauth->password);
    
                    // reset password response
                    return response()->json([
                        'data'=>'Password has been updated.'
                    ],Response::HTTP_CREATED);
                }
            }
    
        }
}
