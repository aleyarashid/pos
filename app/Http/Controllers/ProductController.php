<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    //Product Page
    public function ProductPage(Request $request){
        $user_id = $request->header('id');
        $products = Product::where('user_id', $user_id)->with('category')->latest()->get();
        
        return Inertia::render('ProductPage', [
            'products' => $products
        ]);
    }

    //Product Save Page
    public function ProductSavePage(){
        $user_id = request()->header('id');
        $product_id = request()->query('id');
        $product = Product::where('id', $product_id)->where('user_id', $user_id)->first();
        $categories = Category::where('user_id', $user_id)->get();

        return Inertia::render('ProductSavePage', [
            'product' => $product,
            'categories' => $categories
        ]);
    }
    //Create Product
    public function CreateProduct(Request $request)
    {
        $user_id = $request->header('id'); 
         $request->validate([
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = [
             'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'category_id' => $request->category_id,
            'user_id' => $user_id
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $filePath = 'uploads/'.$fileName;
            $image->move(public_path('uploads/'), $fileName);
            $data['image'] = $filePath;
        }

        Product::create($data);
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Product created successfully'
        // ], 200);
        $data = [
            'message' => 'Product created successfully',
            'status' => true,
            'error' => ''
        ];
        return redirect('/product-page')->with($data);
    }//End Create Product

    //Product List
    public function ProductList()
    {
        $user_id = request()->header('id');
        $products = Product::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }//End Product List

    //Product By Id
    public function ProductById(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            'id' => 'required|exists:products,id'
        ]); 
        $id = $request->input('id');
        $product = Product::where('id', $id)->where('user_id', $user_id)->first();
        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 200);
    }//End Product By Id

    //Update Product
    public function UpdateProduct(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required',
            //'brand_id' => 'required',  
            
        ]);

        $id = $request->query('id');
        $product = Product::where('user_id', $user_id)->findOrFail($id);

       $product->name = $request->input('name');
       $product->price = $request->input('price');
       $product->unit = $request->input('unit');
       $product->category_id = $request->input('category_id');
       //$product->brand_id = $request->input('brand_id');    

        if ($request->hasFile('image')) {
            if($product->image && file_exists(public_path($product->image)))
            {
                unlink(public_path($product->image));
            }
            $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024'
            ]);
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $filePath = 'uploads/'.$fileName;
            $image->move(public_path('uploads/'), $fileName);
            $product->image = $filePath;
        }

        $product->save();
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Product updated successfully'
        // ], 200);
        $data = [
            'message' => 'Product updated successfully',
            'status' => true,
            'error' => ''
        ];
        return redirect('/product-page')->with($data);
    }//End Update Product

    //Delete Product
    public function DeleteProduct(Request $request, $id)
    {
        $user_id = $request->header('id');
        $product = Product::where('user_id', $user_id)->findOrFail($id);
        if($product->image && file_exists(public_path($product->image)))
        {
            unlink(public_path($product->image));
        }
        $product->delete();
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Product deleted successfully'
        // ], 200);
        $data = [
            'message' => 'Product deleted successfully',
            'status' => true,
            'error' => ''
        ];
        return redirect('/product-page')->with($data);
    }//End Delete Product
}