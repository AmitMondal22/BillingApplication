<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SellerMod;
use Illuminate\Http\Request;

class Seller extends Controller
{
    public function add(Request $request){
        SellerMod::create([
            "shop_name" => $request->shop_name,
            "contact" => $request->contact,
            "adress" => $request->adress,
        ]);
        return response()->json([
            "message" => "Seller Added Successfully"
        ], 200);
    }
}
