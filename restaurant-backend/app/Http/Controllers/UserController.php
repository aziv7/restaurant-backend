<?php

namespace App\Http\Controllers;

use App\Models\CoordonneesAuthentification;
use App\Models\Image;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
        $user->{'date de naissance'} = $request->{'date de naissance'};
        $user->{'numero de telephone'} = $request->{'numero de telephone'};
        $user = User::create($request->all());
        /****************************************************/

        /************enregistrer le login et pwd dans la base ****************/
        $user->coordonneesAuthentification()->save($coordonnesauth);
        return $user;


    }

    public function verifyUser(Request $request){
        $verification_code = \Illuminate\Support\Facades\Request::get('code');
        $user = User::where(['verification_code' => $verification_code])->first();
        if($user != null){
            $user->is_verified = 1;
            $user->save();}
        $cookie = cookie('response', $user->is_verified, 60 * 24); // cookie valid for 1 day

        redirect('http://localhost:8100/verification')->withCookie($cookie);}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {/*********** update cordonne and user at the same time ********/
        $user = User::find($id);
        $user->update($request->all());
try{        $coordonnesauth=CoordonneesAuthentification::where('user_id', 'like', $id)->get()->first();
    //var_dump($coordonnesauth);
    $coordonnesauthh=new CoordonneesAuthentification();
    $coordonnesauthh->login=$coordonnesauth->login;

    $coordonnesauthh->password=$coordonnesauth->password;
    var_dump($coordonnesauthh);
     try{
         $coordonnesauthh->password=Hash::make($request->input('password'));

     }catch (Throwable $e){
        // var_dump('there is no passwor');
     }
    try{
        $coordonnesauthh->login=$request->input('login');

    }catch (Throwable $e){
      //  var_dump('there is no login');

    }
        $editdata = array(
            'login'=> $coordonnesauthh->login,
            'password'=>$coordonnesauthh->password
    );
     $coordonnesauth->update($editdata );
}
catch (Throwable $e){

}
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  string  $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return User::where('nom', 'like', '%'.$nom.'%')->get();
    }

    //add image
    public function uploadimg(Request $request, $id)
    {
        $user = User::find($id);
        Image::create(
            ['nom' => $request->get('nomimage'),
                'src' => $request->get('image'),
                'user_id' => $user->id]);
        return $user;
    }
    /**
     * Encode array from latin1 to utf8 recursively
     * @param $dat
     * @return array|string
     */

    public function login(Request $request) {
        //recuprération de login et mdp ou le login est identique au celui récupéré de la requete
        $coodronnees = CoordonneesAuthentification::where('login',$request->login)->first();
        //récupération de l'utilisateur ayan ce login et ce mdp
        $user = $coodronnees->user;
        //echec
        if(!$user || !Hash::check($request->password, $coodronnees->password)){
            return response(array(
                'message' => 'this credentials don"t match',
            ), 403);
        }

        if($user->is_verified==0){
            return response(array(
                'message' => 'verify your email',
            ), 403);
        }
        //succés
        $token = $user->createToken('my-app-token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24); // cookie valid for 1 day
        $response = [
            'jwt'=> $token,
            'user'=> $user
        ];
        return response($response, 201)->withCookie($cookie);
    }
    /**
     * Delete the cookie
     *
     */
    public function logout()
    {
        $cookie = \Cookie::forget('jwt');
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response([
            'message' => 'Success'
        ])->withCookie($cookie);
    }

        public function Connected()
    {return Auth::user();}
////////************   GetUserByIDWithCooordonnes ***************////
    public function GetUserByIdWithCoordonnes($id)
    {
       return User::with(['CoordonneesAuthentification'])->where('id',$id)->get()->first();

    }
    public function GetUsersWithCoordonnes()
    {
        return User::with(['CoordonneesAuthentification'])->get();

    }
}
