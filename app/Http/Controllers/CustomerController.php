<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.dashboard.customer-page");
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
                "name" => "required|string|min:2",
                "email" => "required|string|min:5",
                "mobile" => "required|string|min:6"
            ]);
            $user_id = $request->header("id");
    
             Customer::create([
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "mobile" =>$request->input("mobile"),
                "user_id" => $user_id
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Customer Create Successfully"
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
        return Customer::where("user_id", $user_id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $user_id = $request->header("id");
        $customer_id = $request->input("id");
        return Customer::where("user_id", $user_id)->where("id", $customer_id)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        try{
            $request->validate([
                "name" => "required|string|min:2",
                "email" => "required|string|min:5",
                "mobile" => "required|string|min:6"
        ]);
            $user_id = $request->header("id");
            $customer_id = $request->input("id");

            Customer::where("user_id", $user_id)->where("id", $customer_id)->update([
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "mobile" => $request->input("mobile")
            ]);
            return response()->json([
                "status" => "success",
                "message" => "Customer Update Successfully"
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
        $customer_id = $request->input("id");

        return Customer::where("user_id", $user_id)->where("id", $customer_id)->delete();
    }
}
