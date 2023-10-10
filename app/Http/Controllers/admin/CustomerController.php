<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    function mycustomer(Request $r){
        try {
            $rules = [
                'mobile_no' => 'numeric|required',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400);
            }

            $custData=Customer::where("mobile_no",$r->mobile_no)->first();
            if($custData){
                return response()->json([
                    "data" => $custData,
                    "status" => true
                ], 200);
            }
            return response()->json([
                "data" => $custData,
                "status" => false
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }



    function all_mycustomer(Request $r){
        try {
            $custData=Customer::paginate(20);
            if($custData){
                return response()->json([
                    "data" => $custData,
                    "status" => true
                ], 200);
            }
            return response()->json([
                "data" => $custData,
                "status" => false
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }




    function all_mycustomer_alltrans(Request $r){
        try {
            $data=Transaction::select('*')
            ->join('md_customer as b', 'td_transaction.customer_id', '=', 'b.id')
            ->where('td_transaction.customer_id', $r->id)
            ->get();
            return response()->json([
                "data" => $data,
                "status" => false
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }
}
