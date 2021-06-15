<?php

namespace App\Http\Controllers;

use App\Models\CoordonneesAuthentification;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $user = User::create($request->all());
        /****************************************************/

        /************enregistrer le login et pwd dans la base ****************/
        $user->coordonneesAuthentification()->save($coordonnesauth);
        return $user;
    }

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
    {
        $user = User::find($id);
        $user->update($request->all());
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
    public function login(Request $request) {
        //recuprération de login et mdp ou le login est identique au celui récupéré de la requete
        $coodronnees = CoordonneesAuthentification::where('login',$request->login)->first();
        //récupération de l'utilisateur ayan ce login et ce mdp
        $user = $coodronnees->user;
        //echec
        if(!$user || !Hash::check($request->password, $coodronnees->password)){
            return response([
                'message'=>["this credentials don't match"],
                403
            ]);
        }
        //succés
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token,
        ];
        return response($response, 201);
    }
}
