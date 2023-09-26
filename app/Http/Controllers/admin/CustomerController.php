<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
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
            ], 400);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }
}
