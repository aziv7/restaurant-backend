<?php

namespace Database\Seeders;

use App\Models\CoordonneesAuthentification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Request $request)
    {
       /* DB::table('users')->insert([
            'nom'=> 'John',
            'prenom'=> 'Vierra',
            'email'=> 'John@gmail.com',
            'date de naissance'=> '1993-04-29',
            'numero de telephone'=>'123456',

        ]);*/


        $request->validate([
            'nom'=> 'required',
            'prenom'=> 'required',
            'date de naissance'=> 'required',
            'email'=> 'required',
            'numero de telephone'=> 'required',
        ]);

        $coordonnesauth = new CoordonneesAuthentification();
        $coordonnesauth->login = $request->login;
        $coordonnesauth->password = encrypt($request->password);
        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->{'date de naissance'} = $request->{'date de naissance'};
        $user->{'numero de telephone'} = $request->{'numero de telephone'};
        $user = User::create($request->all());
        //$coordonnesauth = CoordonneesAuthentification::create($request->get('login' && 'password'));
        $user->coordonneesAuthentification()->save($coordonnesauth);
        return $user;
    }
}
