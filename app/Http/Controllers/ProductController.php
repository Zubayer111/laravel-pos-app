<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.dashboard.product-page');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                "name"=>"required|string|min:2|max:225",
                "price"=>"required|string",
                "unit"=>"required|string",
                "img_url" => "required|image|mimes:jpeg,png,jpg,svg|max:10000"
            ]);
            $user_id = $request->header("id");
            $img = $request->file("img_url");
            $time = time();
            $file_name = $img->getClientOriginalName();
            $img_name = "{$user_id}-{$time}-{$file_name}";
            $img_url = "uploads/{$img_name}";
            $img->move(public_path('uploads'),$img_name);

            Product::create([
                "name" => $request->input("name"),
                "price" => $request->input("price"),
                "unit" => $request->input("unit"),
                "img_url" => $img_url,
                "user_id" => $user_id,
                "category_id" => $request->input("category_id")
        ]);
        return response()->json([
            "status" => "success",
            "message" => "Product Create Successfully"
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
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user_id = $request->header("id");
        return Product::where("user_id", $user_id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $user_id = $request->header("id");
        $product_id = $request->input("id");
        return Product::where("user_id",$user_id)->where("id",$product_id)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try{
            $request->validate([
                "name"=>"required|string|min:2|max:225",
                "price"=>"required|string",
                "unit"=>"required|string",
            ]);
    
            $user_id = $request->header("id");
            $product_id = $request->input("id");
    
            if($request->hasFile("img")){
                $img = $request->file("img");
                $time = time();
                $file_name = $img->getClientOriginalName();
                $img_name = "{$user_id}-{$time}-{$file_name}";
                $img_url = "uploads/{$img_name}";
                $img->move(public_path('uploads'),$img_name);
    
                $file_path=$request->input("file_path");
                File::delete($file_path);
    
                 Product::where("user_id", $user_id)->where("id", $product_id)->update([
                    "name" => $request->input("name"),
                    "price" => $request->input("price"),
                    "unit" => $request->input("unit"),
                    "img_url" => $img_url,
                    "user_id" => $user_id,
                    "category_id" => $request->input("category_id")
                ]);
            }
            else{
                 Product::where("user_id", $user_id)->where("id", $product_id)->update([
                    "name" => $request->input("name"),
                    "price" => $request->input("price"),
                    "unit" => $request->input("unit"),
                    "user_id" => $user_id,
                    "category_id" => $request->input("category_id")
                ]);
            }

            return response()->json([
                "status" => "success",
                "message" => "Product Updete Successfully"
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
        $product_id = $request->input("id");
        $file_path = $request->input("file_path");
        //dd($file_path);
        File::delete($file_path);
        return Product::where("user_id", $user_id)->where("id", $product_id)->delete();
    }
}
