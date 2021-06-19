<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\mailController;
use App\Models\CoordonneesAuthentification;
use App\Models\Role;
use App\Models\RoleUser;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom'=> 'required',
            'prenom'=> 'required',
            'date de naissance'=> 'required',
            'email'=> 'required',
            'numero de telephone'=> 'required',
        ]);
        //créer une instance de CoordonneesAuthentification
        $coordonnesauth = new CoordonneesAuthentification();
        $coordonnesauth->login = $request->login;
        //hacher le mdp eissue de la requete
        $coordonnesauth->password =Hash::make($request->password);
        /*********Creation d'un user à partir des informations récupérés de la requete*********/
        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->{'date de naissance'} = $request->{'date de naissance'};
        $user->{'numero de telephone'} = $request->{'numero de telephone'};
        $user->verification_code = sha1(time());
        $user = User::create($request->all());
        /************ donner le role Costumer****************/
        $role_costumer =Role::where(['nom_des_roles' =>'costumer'])->first();//search for the role id of costumer
        $user->roles()->save($role_costumer);
        /****************************************************/

        /************enregistrer le login et pwd dans la base ****************/
        $user->coordonneesAuthentification()->save($coordonnesauth);
        /******************************************************************/

        /*********************sending verification email***************/
        //success
        if($user != null){
        MailController::sendSignupEmail($user->nom, $user->email, $user->verification_code);
    }
        return $user;
    }

    public function verifyUser(Request $request){
        $verification_code = \Illuminate\Support\Facades\Request::get('code');
        $user = User::where(['verification_code' => $verification_code])->first();
        if($user != null){
            $user->is_verified = 1;
            $user->save();
            return $user;
        }

        return 'error';
    }
}
