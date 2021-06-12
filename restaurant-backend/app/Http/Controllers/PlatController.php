<?php

namespace App\Http\Controllers;

use App\Models\Modificateur;
use App\Models\Plat;
use App\Models\Image;
use Illuminate\Http\Request;


class PlatController extends Controller
{
    public function index() {
        return Plat::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required',
            'description' => 'required',
            'image-name'=>'required',
            'image-src'=>'required'
        ]);
$plat=Plat::create(['nom' => $request->get('nom'),
        'prix' => $request->get('prix'),'description' => $request->get('description'),]) ;
        $image =Image::create(['nom' => $request->get('image-name'),
        'src' => $request->get('image-src'),'plat_id'=>$plat->id]) ;
        
        
        return $plat;
    }

    public function show($id)
    {
        return Plat::find($id);
    }

    public function update(Request $request, $id)
    {
        $plat = Plat::find($id);
        $plat->update($request->all());
        return $plat;
    }


    public function setIngredient(Request $request, $id)
    {
        $plat = Plat::find(1);
        
        $ingredient=Ingredient::find($id);

        
        $plat->ingredients()->attach($ingredient);
        return $plat;
    }

    /** affecter un plat a un modificateur */
    public function addPlatToModificateur(Request $request, $id_plat,$id_modificateur)
    {
        $plat = Plat::find($id_plat);
        
        $modificateur=Modificateur::find($id_modificateur);

        
        $plat->modificateurs()->attach($modificateur);
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
     * @param  string  $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Plat::where('nom', 'like', '%'.$nom.'%')->get();
    }
}
