<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sales;
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


    function stock_product(Request $r)
    {


        try {
            $searchKeyword = $r->search;
            $checkingData = StorIn::join("model AS b", 'b.model_id', '=', 'td_product_store.model_id')
                ->join("procuct AS c", 'c.product_id', '=', 'b.product_id')
                ->join("company_list AS d", 'd.company_id', '=', 'b.company_id')
                ->whereIn('sales_flags', ['N', 'P'])
                ->where(function ($query) use ($searchKeyword) {
                    $query->where('b.model_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('c.product_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.serial_number', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.purchase_rate', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.sales_rate', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.purchase_date', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.warranty_expired', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.cgst_p', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.sgst_p', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('td_product_store.created_at', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('d.company_name', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->select("td_product_store.*", "b.model_name", "c.product_name", "d.company_name")->paginate(20);
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
                ->where("td_product_store.serial_number", $r->serial_number)
                ->select("td_product_store.*", "b.model_name", "c.product_name", "d.company_name")->get();
            return response()->json([
                "data" => $checkingData,
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

    // function find_barcode(){

    // }


    function billing(Request $r)
    {
        try {
            $rules = [
                "sl_no" => 'required',
                "customer_id" => 'numeric',
                'c_name' => 'required',
                'p_num' => 'numeric',
                'c_add' => 'string',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400);
            }


            if (0 == $r->c_id) {
                $data = Customer::create([
                    "name" => $r->c_name,
                    "mobile_no" => $r->p_num,
                    "adress" => $r->c_add,
                    "password" => Hash::make($r->p_num),
                    "role" => "PU",
                    "otp" => 0,
                    "otp_status" => 'A',
                    "user_type" => "PU",
                    "deleted_flag" => 'N',
                    "created_by" => auth()->user()->id
                ]);
                $userid = $data->id;
            } else {
                $userid = $r->c_id;
            }


            Sales::select("billing_id")->latest()->first();
            foreach ($r->sl_no as $stordata) {
                // return $stordata['barcode_no'];
                Sales::create([
                    "billing_id" => 1,
                    "stock_id" => $stordata['barcode_no'],
                    "price" => $stordata['price'],
                    "payment_flag" =>'P',
                    "cust_id"=>$userid,
                    "billingdate" => date('Y-m-d'),
                    "created_by" => auth()->user()->id,
                ]);

                StorIn::where('product_store_id',$stordata['product_store_id'])->update([
                    "sels_warranty"=>$stordata['warranty']
                ]);
            }
            return response()->json("success", 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }
}
