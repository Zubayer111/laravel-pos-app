<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.category-page');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function categoryList(Request $request)
    {
        $user_id = $request->header("id");
        return Category::where("user_id", $user_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                "name"=>"required|string|min:2"
            ]);
            $user_id = $request->header("id");
            //dd($user_id);
             Category::create([
                "name" => $request->input("name"),
                "user_id" => $user_id 
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Category Create Successfully"
            ], 200);
        }
        catch(Exception $e){
            return response()->json([
                "status" => "Failed",
                "message" => $e->getMessage()
            ], 200);
        }
        
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        
        $category_id = $request->input("id");
        $user_id = $request->header("id");

        return Category::where("user_id", $user_id)->where("id", $category_id)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try{
            $request->validate([
                "name"=>"required|string|min:2"
            ]);
            $user_id = $request->header("id");
            $category_id = $request->input("id");
             Category::where("user_id", $user_id)->where("id", $category_id)->update([
                "name" => $request->input("name")
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Category Update Successfully"
            ], 200);
        }
        catch(Exception $e){
            return response()->json([
                "status" => "Failed",
                "message" => $e->getMessage()
            ], 200);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user_id = $request->header("id");
        $category_id = $request->input("id");
        return Category::where("user_id", $user_id)->where("id", $category_id)->delete();
    }

    
}
