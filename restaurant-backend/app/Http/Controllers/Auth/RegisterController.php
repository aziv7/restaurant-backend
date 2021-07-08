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
use Illuminate\Support\Facades\DB;
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
            'date_de_naissance'=> 'required',
            'email'=> 'required',
            'numero_de_telephone'=> 'required',
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
        $user->date_de_naissance = $request->date_de_naissance;
        $user->numero_de_telephone = $request->numero_de_telephone;
        $user->verification_code = sha1(time());
        DB::insert('insert into users (nom, prenom, email,date_de_naissance,numero_de_telephone,
                   verification_code,created_at,updated_at) values(?,?,?,?,?,?,?,?)', [$request->nom, $request->prenom,
            $request->email, $request->date_de_naissance, $request->numero_de_telephone, $user->verification_code,Carbon::Now(),Carbon::Now()]);
        $user = User::where(['verification_code' => $user->verification_code])->first();
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
        mailController::sendSignupEmail($user->nom, $user->email, $user->verification_code);
    }
        return $user;
    }

    public function verifyUser(Request $request){
        $verification_code = \Illuminate\Support\Facades\Request::get('code');
      //  var_dump($verification_code);
        $user = User::where(['verification_code' => $verification_code])->first();// var_dump($user);
        if($user != null){
            $user->is_verified = 1;
            $user->save();
        }
        $cookie = cookie('response', $user->is_verified, 60 * 24); // cookie valid for 1 day

        return redirect('http://localhost:8100/verification')->withCookie($cookie);
    }
}
