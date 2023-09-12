<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\BusinessInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Hash;

class SellersLabel extends Controller
{
    function createSellers(Request $r)
    {
        // try {
            $otp = sprintf("%06d", mt_rand(000001, 999999));

            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|numeric',
                'outlet_type' => 'required|string|in:DL,W,DI,R,O',
                'business_name' => 'required|string',
                'business_short_name' => 'required|string',
                'adress' => 'required|string',
                'business_location' => 'required|numeric',
                'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400); //400 envalies responce
            }
            $data = ModelsUser::create([
                'name' => $r->name,
                'email' => $r->email,
                'mobile_no' => $r->mobile,
                'password' => Hash::make($r->password),
                'role' => 'U',
                'otp' => 0,
                'otp_status' => 'A',
                "sellers_id" => 1,
                "sellers_type" => $r->outlet_type,
                "business_id" => 1,
                "user_type" => "A",
                "created_at" => auth()->user()->id,
                "deleted_flag" => "N",
                "created_by" => 1
            ]);
            $data->id;
            $bisineddDAta=BusinessInfo::create([
                "business_id" => $data->id,
                "business_name" => $r->business_name,
                "business_short_name" => $r->business_short_name,
                "adress" => $r->adress,
                "business_mobile" => $r->mobile,
                "business_email" => $r->email,
                "business_location" => $r->business_location,
                "business_type" => $r->outlet_type,
                "created_by" => auth()->user()->id,
                "deleted_flag" => "N"
            ]);

            // $this->send_mail_opt($r->email, $otp);
            return response()->json([$data,$bisineddDAta], 200);
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     $data = [
        //         "ERROR" => $th,
        //         "STATUS" => 0,
        //     ];
        //     return response()->json($data, 400);
        // }
    }
}
