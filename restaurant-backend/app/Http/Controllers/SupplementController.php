<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplement;
class SupplementController extends Controller
{
    public function index() {
        return Supplement::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prix' => 'required',
            'quantite' => 'required',
            'image'=>'required',
            
        ]);
$supplement=Supplement::create($request->all()) ;
        
        
        
        return $supplement;
    }


   

    public function show($id)
    {
        return Supplement::find($id);
    }

    public function update(Request $request, $id)
    {
        $supplement = Supplement::find($id);
        $supplement->update($request->all());
        return $supplement;
    }


    

    


    
    


    public function destroy($id)
    {
        return Supplement::destroy($id);
    }

    /**
     * Search for a name
     *
     * @param  string  $nom
     * @return \Illuminate\Http\Response
     */
    public function search($nom)
    {
        return Supplement::where('nom', 'like', '%'.$nom.'%')->get();
    }
}
