<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\StorIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                "cgst_p" => 'required|numeric',
                "sgst_p" => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400);
            }


            foreach ($r->serial_number as $slno) {

                StorIn::create([
                    "model_id" => $r->model_id,
                    "serial_number" => $slno,
                    "purchase_rate" => $r->purchase_rate,
                    "sales_rate" => $r->sales_rate,
                    "purchase_by" => $r->purchase_by,
                    "purchase_date" => $r->purchase_date,
                    "warranty_expired" => $r->warranty_expired,
                    "cgst_p" => $r->cgst_p,
                    "sgst_p" => $r->sgst_p,
                    "customer_id" => auth()->user()->id,
                    "created_by" => auth()->user()->id
                ]);
            }
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
                "cgst_p" => 'required|numeric',
                "sgst_p" => 'required|numeric',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400);
            }
            $upatateData = StorIn::where("product_store_id", $r->product_store_id)->where("sales_flags", "N")
                ->update([
                    "model_id" => $r->model_id,
                    "serial_number" => $r->serial_number,
                    "purchase_rate" => $r->purchase_rate,
                    "sales_rate" => $r->sales_rate,
                    "purchase_by" => $r->purchase_by,
                    "purchase_date" => $r->purchase_date,
                    "warranty_expired" => $r->warranty_expired,
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



    function delete_stock(Request $r)
    {
        try {
            $rules = [
                'product_store_id' => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400); //400 envalies responce
            }

            $data = StorIn::where("product_store_id", $r->product_store_id)->where("sales_flags", "N")->delete();
            return response()->json([
                "data" => $data,
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function stock_product()
    {
        try {
            $checkingData = StorIn::join("model AS b", 'b.model_id', '=', 'td_product_store.model_id')
                ->join("procuct AS c", 'c.product_id', '=', 'b.product_id')
                ->join("company_list AS d", 'd.company_id', '=', 'b.company_id')
                ->whereIn('sales_flags', ['N', 'P'])
                ->select("td_product_store.*", "b.model_name", "c.product_name", "d.company_name")->paginate(25);
            return response()->json([
                "data" => $checkingData,
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function find_product(Request $r)
    {
        try {

            $rules = [
                "serial_number" => 'required'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400);
            }

            $checkingData = StorIn::join("model AS b", 'b.model_id', '=', 'td_product_store.model_id')
                ->join("procuct AS c", 'c.product_id', '=', 'b.product_id')
                ->join("company_list AS d", 'd.company_id', '=', 'b.company_id')
                ->whereIn('sales_flags', ['N', 'P'])
                ->where("td_product_store.serial_number", 'like', '%' . $r->serial_number . '%')
                ->select("td_product_store.*", "b.model_name", "c.product_name", "d.company_name")->paginate(1);
            return response()->json([
                "data" => $checkingData,
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function billing(Request $r)
    {
        // try {
        //     $rules = [
        //         "serial_number" => 'required',
        //         "customer_id" => 'string',
        //         'name' => 'required',
        //         'mobile' => 'numeric',
        //         'address' => 'string',
        //     ];
        //     $valaditor = Validator::make($r->all(), $rules);
        //     if ($valaditor->fails()) {
        //         return response()->json($valaditor->errors(), 400);
        //     }
        //     $userid=$r->customer_id;

        //     if (!$r->customer_id) {
        //         $data=Customer::create([
        //             "name"=>$r->name,
        //             "mobile_no"=>$r->mobile,
        //             "adress"=>$r->address,
        //             "password"=>Hash::make($r->mobile),
        //             "role"=>"PU",
        //             "otp"=>0,
        //             "otp_status"=>'A',
        //             "user_type"=>"PU",
        //             "deleted_flag"=>'N',
        //             "created_by"=>auth()->user()->id
        //         ]);
        //         $userid=$data->id;
        //     }




        // } catch (\Throwable $th) {
        //     return response()->json($th, 400);
        // }
    }
}
