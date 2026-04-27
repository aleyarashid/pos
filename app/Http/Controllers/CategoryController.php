<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //Category Page
    public function CategoryPage(Request $request){
        $user_id = $request->session()->get('user_id','default');
        $categories = Category::where('user_id', $user_id)->get();
        return Inertia::render('CategoryPage',[
            'categories' => $categories 
        ]);

    }
    //Category Save Page
    public function CategorySavePage(Request $request){
        $user_id = $request->session()->get('user_id','default');
        $category_id = $request->query('id');
        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();
        return Inertia::render('CategorySavePage',[
            'category' => $category
        ]);
    }
    //Create Category
    public function CreateCategory(Request $request)
    {
        $user_id = $request->session()->get('user_id','default');

        Category::create([
            'name' => $request->input('name'),
            'user_id' => $user_id
        ]);

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Category {{}} created successfully'
        // ], 200);
        $data = [
            'message' => 'Category created successfully',
            'status' => true,
            'error' => ''
        ];
        return redirect('/category-page')->with($data);
    } //End Create Category

    //Category List
    public function CategoryList(Request $request)
    {
        $user_id = $request->header('id');
        $categories = Category::where('user_id', $user_id)->get();
        return $categories;
    }   //End Category List

    //Category By Id
    public function CategoryById(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        $category = Category::where('id', $id)->where('user_id', $user_id)->first();
        return $category;
    }   //End Category By Id

    //Update Category
    public function UpdateCategory(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        Category::where('id', $id)->where('user_id', $user_id)->update([
                'name' => $request->input('name')
            ]);

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Category updated successfully'
            // ], 200);
            $data = [
                'message' => 'Category updated successfully',
                'status' => true,
                'error' => ''
            ];
            return redirect('/category-page')->with($data);
        
    }   //End Update Category   

    //Delete Category
    public function DeleteCategory(Request $request, $id)
    {
        $user_id = $request->session()->get('user_id','default');
        //$id = $request->input('id');
        Category::where('id', $id)->where('user_id', $user_id)->delete();
        $data = [
            'message' => 'Category deleted successfully',
            'status' => true,
            'error' => ''
        ];
        return redirect('/category-page')->with($data);
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Category deleted successfully'
        // ], 200);

    
    } //End Delete Category
}
