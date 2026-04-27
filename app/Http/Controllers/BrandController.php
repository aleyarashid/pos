<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    //Create Brand
    public function CreateBrand(Request $request)
    {   
        $user_id = $request->header('id'); 
        $request->validate([
            'name' => 'required'
        ]);       
        Brand::create([
            'name' => $request->input('name'),
            'user_id' => $user_id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Brand created successfully'
        ], 200);
    }//End Create Brand
}
