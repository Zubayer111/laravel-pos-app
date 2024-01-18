<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function InvoicePage():View{
        return view('pages.dashboard.invoice-page');
    }

    public function SalePage():View{
        return view('pages.dashboard.sale-page');
    }
    
    public function create(Request $request){
        DB::beginTransaction();
        try{
            $request->validate([
                "total" => "required|string",
                "discount" => "required|string",
                "vat" => "required|string",
                "payable" => "required|string",
                "customer_id" => "required|exists:customers,id"
            ]);
            $user_id = $request->header("id");
            $total = $request->input("total");
            $discount = $request->input("discount");
            $vat = $request->input("vat");
            $payable = $request->input("payable");
            $customer_id = $request->input("customer_id");
            
            $invoice = Invoice::create([
                "total" => $total,
                "discount" => $discount,
                "vat" => $vat,
                "payable" => $payable,
                "user_id" => $user_id,
                "customer_id" => $customer_id
            ]);
            $invoice_id = $invoice->id;
            $products = $request->input("products");

            foreach($products as $product){
                InvoiceProduct::create([
                    "user_id" => $user_id,
                    "invoice_id" => $invoice_id,
                    "product_id" => $product["product_id"],
                    "qty" => $product["qty"],
                    "sale_price" => $product["sale_price"]
                ]);
            }
            DB::commit();
            return response()->json([
                "status" => "success",
                "message" => "Invoice Create Successfully"
            ], 200);
        }
        catch(Exception $e){
            DB::rollBack();
            return response()->json([
                "status" => "Failed",
                "message" => $e->getMessage()
            ], 200);
        }
    }

    public function select(Request $request){
        $user_id = $request->header("id");
        return Invoice::where("user_id", $user_id)->with("customer")->get();
    }

    public function details(Request $request){
        $request->validate([
            "inv_id" => "required|exists:invoices,id",
            "cus_id" => "required|exists:customers,id"
        ]);
        $user_id = $request->header("id");
        $customer_id = $request->input("cus_id");
        $invoice_id = $request->input("inv_id");

        $customerDetails = Customer::where("user_id", $user_id)
        ->where("id", $customer_id)->first();

        $invoiceTotal = Invoice::where("user_id", $user_id)
        ->where("id", $invoice_id)->first();
        
        $invoiceProduct = InvoiceProduct::where("user_id", $user_id)
        ->where("invoice_id", $invoice_id)->with("product")->get();

        return array(
            "customer" => $customerDetails,
            "invoice" => $invoiceTotal,
            "product" => $invoiceProduct
        );
    }

    public function destroy(Request $request){
        DB::beginTransaction();
        try{
            $request->validate([
                "inv_id" => "required|exists:invoices,id"
            ]);
            $user_id = $request->header("id");
            $invoice_id = $request->input("inv_id");

            InvoiceProduct::where("user_id", $user_id)->where("invoice_id", $invoice_id)->delete();
            Invoice::where("id", $invoice_id)->delete();

            DB::commit();
            return response()->json([
                "status" => "success",
                "message" => "Invoice Delete Successfully"
            ], 200);
        }
        catch(Exception $e){
            DB::rollBack();
            return response()->json([
                "status" => "Failed",
                "message" => $e->getMessage()
            ], 200);
        }
    }
}
