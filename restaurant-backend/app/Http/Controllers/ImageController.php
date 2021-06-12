<?php

namespace App\Http\Controllers;
use App\Models\Plat;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index() {
        return Image::all();
    }

    public function storeImage(Request $request,$id)
    {
        $request->validate([
            'nom' => 'required',
            'src' => 'required',
            
        ]);
        $plat=Plat::find($id);
        $image = new Image([
            'nom' => $request->get('nom'),
            'src' => $request->get('src'),
            'plat_id' => $plat
           
        ]);
        return $image->save();
    }

    public function showPlats($id)
    {
       $image=  Image::find($id);

         return $image->plats();
    }


    public function show($id)
    {
        return Image::find($id);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        $image->update($request->all());
        return $image;
    }



    public function destroy($id)
    {
        return Image::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  string  $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Image::where('nom', 'like', '%'.$nom.'%')->get();
    }
}
