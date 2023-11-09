<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            $cust=Customer::where('id',$r->id)->first();
            $data=Transaction::select('*')
            //->join('md_customer as b', 'td_transaction.customer_id', '=', 'b.id')
            ->where('td_transaction.customer_id', $r->id)
            ->get();
            return response()->json([
                "data" => ["trans_details"=>$data,"personal_details"=>$cust],
                "status" => false
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

    function customer_deposit(Request $r){
        try {

            $data=Transaction::create([
                "payment_flag"=>'A',
                "amount"=>$r->amount,
                "customer_id"=>$r->customer_id,
                "transaction_date"=>date('Y-m-d'),
                "created_by"=>auth()->user()->id
                ]);

            return response()->json([
                "data" => $data,
                "status" => false
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    public function add_mycustomer(Request $r){
        try {
            $cust=Customer::create([
                'name'=>$r->name,
                'adress'=>$r->adress,
                'mobile_no'=>$r->mobile_no,
                'password'=>Hash::make($r->mobile),
                'role'=>'PU',
                'otp'=>0,
                'otp_status'=>'A',
                "user_type"=>"PU",
                "deleted_flag"=>"N"
                ]);

        return response()->json([
                "data" => $cust,
                "status" => true
            ], 200);

        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }



    public function edit_mycustomer(Request $r){
        try {
            $cust=Customer::where("id",$r->id)->update([
                'name'=>$r->name,
                'adress'=>$r->adress,
                'mobile_no'=>$r->mobile_no,
                'password'=>Hash::make($r->mobile),
                'role'=>'PU',
                'otp'=>0,
                'otp_status'=>'A',
                "user_type"=>"PU",
                "deleted_flag"=>"N"
                ]);

        return response()->json([
                "data" => $cust,
                "status" => true
            ], 200);

        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

}
