<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Billing extends Controller
{
    function billing_date(Request $r){
        try {
            $data=DB::table('td_sales as a')
            ->select("a.sales_id","a.billing_id","a.stock_id","a.price", "a.billingdate","a.cust_id","a.created_by",
            "b.product_store_id","b.serial_number","b.sales_rate", "b.sels_warranty","b.exchange_product_id","b.cgst_p","b.sgst_p","c..model_name","d.product_name","e.company_name")
            ->join('td_product_store as b','b.serial_number' , '=', 'a.stock_id')
            ->join('model as c','c.model_id' , '=', 'b.model_id')
            ->join('procuct as d','d.product_id' , '=', 'c.product_id')
            ->join('company_list as e','e.company_id' , '=', 'c.company_id')
            ->where("a.billing_id",$r->billing_id)
            ->get();



            $cust=DB::table('td_transaction as a')->select('b.id','b.name','b.mobile_no','b.adress')
            ->join("md_customer as b",'b.id','=','a.customer_id')->where('a.billing_id',$r->billing_id)->first();

            $resData=[
                "list"=>$data,
                'customer'=>$cust
            ];

            return response()->json($resData, 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }



}
