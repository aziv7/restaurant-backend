<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ChangePasswordController extends Controller
{
    public function passwordResetProcess(UpdatePasswordRequest $request){
        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }

    // Verify if token is valid
    private function updatePasswordRow($request){
        $user= DB::table('users')->where('email' , $request->email)->first();
        if($user) {
                return  DB::table('coordonnees_authentifications')
                    ->where('user_id' , $user->id);

        }
    }

    // Token not found response
    private function tokenNotFoundError() {
        return response()->json([
            'error' => 'Either your email or token is wrong.'
        ],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // Reset password
    private function resetPassword($request) {
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
                    'token' => null
                ]);
                var_dump($coordauth->password);

                // reset password response
                return response()->json([
                    'data'=>'Password has been updated.'
                ],Response::HTTP_CREATED);
            }
        }

    }
}
