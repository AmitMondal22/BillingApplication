<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\StorIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class StoreStock extends Controller
{
    function stockIn(Request $r)
    {
        try {
            $rules = [
                "model_id" => 'required|numeric',
                "serial_number" => 'required',
                "purchase_rate" => 'required|numeric',
                "sales_rate" => 'required|numeric',
                "purchase_by" => 'required|numeric',
                "purchase_date" => 'required',
                "warranty_expired" => 'required',
                "sels_warranty" => 'required',
                "cgst_p" => 'required|numeric',
                "sgst_p" => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400);
            }
            StorIn::create([
                "model_id" => $r->model_id,
                "serial_number" => $r->serial_number,
                "purchase_rate" => $r->purchase_rate,
                "sales_rate" => $r->sales_rate,
                "purchase_by" => $r->purchase_by,
                "purchase_date" => $r->purchase_date,
                "warranty_expired" => $r->warranty_expired,
                "sels_warranty" => $r->sels_warranty,
                "cgst_p" => $r->cgst_p,
                "sgst_p" => $r->sgst_p,
                "customer_id" => auth()->user()->id,
                "created_by" => auth()->user()->id
            ]);



            return response()->json([
                "data" => "Stock In Success !",
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }



    function edit_stockIn(Request $r)
    {
        try {
            $rules = [
                "product_store_id" => 'required|numeric',
                "model_id" => 'required|numeric',
                "serial_number" => 'required',
                "purchase_rate" => 'required|numeric',
                "sales_rate" => 'required|numeric',
                "purchase_by" => 'required|numeric',
                "purchase_date" => 'required',
                "warranty_expired" => 'required',
                "sels_warranty" => 'required',
                "cgst_p" => 'required|numeric',
                "sgst_p" => 'required|numeric',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400);
            }
            $upatateData=StorIn::where("product_store_id",$r->product_store_id)->where("sales_flags","Y")->update([
                "model_id" => $r->model_id,
                "serial_number" => $r->serial_number,
                "purchase_rate" => $r->purchase_rate,
                "sales_rate" => $r->sales_rate,
                "purchase_by" => $r->purchase_by,
                "purchase_date" => $r->purchase_date,
                "warranty_expired" => $r->warranty_expired,
                "sels_warranty" => $r->sels_warranty,
                "cgst_p" => $r->cgst_p,
                "sgst_p" => $r->sgst_p,
                "update_by" => auth()->user()->id
            ]);



            return response()->json([
                "data" => $upatateData,
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }
}
