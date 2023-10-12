<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\StorIn;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                "c_id" => 'numeric|required',
                'c_name' => 'required',
                'p_num' => 'numeric|required',
                'c_add' => 'string|required',
                'total_amt' => 'numeric|required',
                'paid_amt' => 'numeric|required',
                'paid_status' => 'string|required',

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
            $pymentStatus = ($r->paid_status == "paid") ? 'P' : 'D';

            // $databar = Sales::select("billing_id")->latest()->first()?Sales::select("billing_id")->latest()->first():(['billing_id' => 0]);


            $databar = Sales::latest()->first();
            $bill_id= $databar ? $databar->billing_id : 0;


            foreach ($r->sl_no as $stordata) {
                // return $stordata['barcode_no'];
                Sales::create([
                    "billing_id" => $bill_id + 1,
                    "stock_id" => $stordata['barcode_no'],
                    "price" => $stordata['price'],
                    "payment_flag" => $pymentStatus,
                    "cust_id" => $userid,
                    "billingdate" => date('Y-m-d'),
                    "created_by" => auth()->user()->id,
                ]);
                StorIn::where('product_store_id', $stordata['product_store_id'])->update([
                    "sels_warranty" => $stordata['warranty'],
                    "sales_flags"=>"Y"
                ]);
            }

            Transaction::create([
                "billing_id" => $bill_id + 1,
                "payment_flag" => $pymentStatus,
                "amount" => $r->total_amt,
                "customer_id" => $userid,
                "transaction_date" => date('Y-m-d'),
                "created_by" => auth()->user()->id
            ]);


if($pymentStatus=='D'){
    Transaction::create([
                "billing_id" => $bill_id + 1,
                "payment_flag" => 'A',
                "amount" => $r->paid_amt,
                "customer_id" => $userid,
                "transaction_date" => date('Y-m-d'),
                "created_by" => auth()->user()->id
            ]);
}


            $resData=[
                'bill_id'=>($bill_id + 1),
                'status'=>"success"
            ];

            return response()->json($resData, 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    public function allbill(Request $r)
    {
        try {

            $searchKeyword = $r->search;

            $data=DB::table('td_sales as a')
            ->select('b.name', 'b.mobile_no', 'a.billing_id', DB::raw('SUM(a.price) as total_price'), DB::raw('MAX(a.created_by) as created_by'), DB::raw('MAX(a.billingdate) as billingdate'))
            ->join('md_customer as b', 'b.id', '=', 'a.cust_id')

            ->where(function ($query) use ($searchKeyword) {
                $query->where('b.name', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('b.mobile_no', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('a.billing_id', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('a.price', 'LIKE', '%' . $searchKeyword . '%')
                    ->orWhere('a.created_by', 'LIKE', '%' . $searchKeyword . '%');
                    // ->orWhere(DB::raw('SUM(a.price)'), 'LIKE', '%' . $searchKeyword . '%');
                    // ->orWhere('total_price', 'LIKE', '%' . $searchKeyword . '%');
            })
            ->groupBy('b.name', 'b.mobile_no', 'a.billing_id')->paginate(20);






            return response()->json($data, 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }
}
