<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Events\test;
use App\Models\Modificateur;
use App\Models\Commande;
use App\Models\Plat;
use App\Models\Ingredient;
use App\Models\Image;
use App\Models\Custom;
use Illuminate\Http\Request;

use App\Models\Supplement;
use PHPUnit\Util\Test as UtilTest;

class PlatController extends Controller
{
    public function index()
    {
        return Plat::with('modificateurs', 'images')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required',
            'description' => 'required',
            'image-name' => 'required',
            'image-src' => 'required'
        ]);
        $plat = Plat::create([
            'nom' => $request->get('nom'),
            'prix' => $request->get('prix'),
            'description' => $request->get('description'),
        ]);

        /*foreach ($request->get('image-name') as $i => $item) {
            var_dump($i);
            var_dump($item);
            var_dump($request->get('image-src')[$i]);
        }*/

        foreach ($request->get('image-name') as $i => $item) {
            $image_name = $item;
            $image_src = $request->get('image-src')[$i];
            Image::create([
                'nom' => $image_name,
                'src' => $image_src,
                'plat_id' => $plat->id
            ]);
        }

        /* if ($plat) {
            broadcast(new Test($plat));
        } */
        return $plat;
    }


    public function addImageToPlat(Request $request, $id)
    {
        $request->validate([

            'image-name' => 'required',
            'image-src' => 'required'
        ]);
        $plat = Plat::find($id);
        $image = Image::create([
            'nom' => $request->get('image-name'),
            'src' => $request->get('image-src'),
            'plat_id' => $plat->id
        ]);


        return $plat;
    }

    public function show($id)
    {
        return Plat::find($id);
    }


    //get plats supplements
    public function getSupplements($id)
    {
        $plat = Plat::find($id);

        return $plat->supplements;
    }

    public function update(Request $request)
    {
        $plat = Plat::find($request->get('id'));
        if ($request->get('image-src')) {
            foreach ($request->get('image-name') as $i => $item) {
                $image_name = $item;
                $image_src = $request->get('image-src')[$i];
                Image::create([
                    'nom' => $image_name,
                    'src' => $image_src,
                    'plat_id' => $plat->id
                ]);
            }
        }
        $plat->update($request->all());
        return $plat;
    }


    public function setIngredient(Request $request, $id)
    {
        $plat = Plat::find(1);

        $ingredient = Ingredient::find($id);


        $plat->ingredients()->attach($ingredient);
        return $plat;
    }

    public function unsetIngredient(Request $request, $id)
    {
        $plat = Plat::find(1);

        $ingredient = Ingredient::find($id);


        $plat->ingredients()->detach($ingredient);
        return $plat;
    }

    /** affecter un plat a un modificateur */
    public function addPlatToModificateur($id_plat, $id_modificateur)
    {
        $plat = Plat::find($id_plat);
        $modificateur = Modificateur::find($id_modificateur);
        $plat->modificateurs()->attach($modificateur);
        return $plat;
    }

    /** détacher un plat d'un modificateur */
    public function detachPlatFromModificateur($id_plat, $id_modificateur)
    {
        $plat = Plat::find($id_plat);
        $modificateur = Modificateur::find($id_modificateur);
        $plat->modificateurs()->detach($modificateur);
        return $plat;
    }

    /** affecter un plat a un custom */
    public function addPlatToCustom($id_plat, $id_custom)
    {
        $plat = Plat::find($id_plat);
        $custom = Custom::find($id_custom);
        $plat->customs()->attach($custom);
        return $plat;
    }

    /** detacher un plat a un custom */
    public function detachPlatFromCustom($id_plat, $id_custom)
    {
        $plat = Plat::find($id_plat);
        $custom = Custom::find($id_custom);
        $plat->customs()->detach($custom);
        return $plat;
    }

    public function getModificateurs(Request $request, $id_plat)
    {
        $plat = Plat::find($id_plat);


        return $plat->modificateurs;
    }


    public function destroy($id)
    {
        return Plat::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param string $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Plat::where('nom', 'like', '%' . $nom . '%')->get();
    }

    /**
     *affectation commande plat
     */
    public function getPlat()
    {
        // $plat = DB::table('plats')->leftJoin('modificateur_plat','=','')->leftJoin('modificateurs')->leftJoin('ingredient_modificateur')->leftJoin('ingredients')->get();
        $plat = Plat::with('modificateurs', 'images', 'ratings', 'modificateurs.ingredients')->get();
        return $plat;
    }
    public function addCommande($id_commande, $id_plat)
    {
        $plat = Plat::find($id_plat);
        $commande = Commande::find($id_commande);
        $plat->Commandes()->save($commande);
        return $plat;
    }
}
