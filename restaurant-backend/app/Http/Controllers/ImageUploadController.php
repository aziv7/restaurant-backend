<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    //
    public function imageUploadPost(Request $request)

    {

        $request->validate([

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);



        $imageName = time() . '.' . $request->image->extension();

        $filePath = $request->image->storeAs('public', $imageName);

        /*  $request->image->move(public_path('images'), $imageName);*/



        /* Store $imageName name in DATABASE from HERE */



        return  $imageName;
    }
}
