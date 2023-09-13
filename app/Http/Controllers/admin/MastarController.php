<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyName;
use App\Models\MastarModel;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MastarController extends Controller
{
    function add_company_name(Request $r)
    {
        try {
            $rules = [
                'company_name' => 'required|string',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 401); //400 envalies responce
            }
            $checkingData = CompanyName::where("company_name", $r->company_name)->where("customer_id", auth()->user()->id)->first();
            if (empty($checkingData)) {
                $data = CompanyName::create([
                    "company_name" => $r->company_name,
                    "customer_id" => auth()->user()->id,
                    "created_by" => auth()->user()->id
                ]);
                return response()->json([
                    "data" => $data,
                    "status" => true
                ], 200);
            } else {
                return response()->json([
                    "data" => "already exist this company name !",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function company_name()
    {
        try {
            $checkingData = CompanyName::where("customer_id", auth()->user()->id)->get();
            return response()->json([
                "data" => $checkingData,
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function edit_company_name(Request $r)
    {
        try {
            $rules = [
                'company_name' => 'required|string',
                'company_id' => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400); //400 envalies responce
            }
            $checkingData = CompanyName::where("company_id", $r->company_id)->where("customer_id", auth()->user()->id)->first();
            if (!empty($checkingData)) {
                $checkingData2 = CompanyName::where("company_id", $r->company_id)->where("customer_id", auth()->user()->id)->first();
                if (!empty($checkingData2)) {
                    $data = CompanyName::where("company_id", $r->company_id)->update([
                        "company_name" => $r->company_name,
                        "updated_by" => auth()->user()->id
                    ]);
                    return response()->json([
                        "data" => $data,
                        "status" => true
                    ], 200);
                } else {
                    return response()->json([
                        "data" => "already exist this company name !",
                        "status" => false
                    ], 400);
                }
            } else {
                return response()->json([
                    "data" => "wrong information",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

    function delete_company_name(Request $r)
    {
        try {
            $rules = [
                'company_id' => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 401); //400 envalies responce
            }
            $checkingData = CompanyName::where("company_id", $r->company_id)->where("customer_id", auth()->user()->id)->first();
            if (!empty($checkingData)) {
                $data = CompanyName::where("company_id", $r->company_id)->delete();
                return response()->json([
                    "data" => $data,
                    "status" => true
                ], 200);
            } else {
                return response()->json([
                    "data" => "wrong information",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

    //add product


    function add_product_name(Request $r)
    {
        try {
            $rules = [
                'product_name' => 'required|string',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 401); //400 envalies responce
            }
            $checkingData = Product::where("product_name", $r->product_name)->where("customer_id", auth()->user()->id)->first();
            if (empty($checkingData)) {
                $data = Product::create([
                    "product_name" => $r->product_name,
                    "customer_id" => auth()->user()->id,
                    "create_by" => auth()->user()->id
                ]);
                return response()->json([
                    "data" => $data,
                    "status" => true
                ], 200);
            } else {
                return response()->json([
                    "data" => "already exist this Product name !",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function product_name()
    {
        try {
            $checkingData = Product::where("customer_id", auth()->user()->id)->get();
            return response()->json([
                "data" => $checkingData,
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function edit_product_name(Request $r)
    {
        try {
            $rules = [
                'product_name' => 'required|string',
                'product_id' => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 401); //400 envalies responce
            }
            $checkingData = Product::where("product_id", $r->product_id)->where("customer_id", auth()->user()->id)->first();
            if (!empty($checkingData)) {
                $checkingData2 = Product::where("product_name", $r->product_name)->where("customer_id", auth()->user()->id)->first();
                if (empty($checkingData2)) {
                    $data = Product::where("product_id", $r->product_id)->update([
                        "product_name" => $r->product_name,
                        "updated_by" => auth()->user()->id
                    ]);
                    return response()->json([
                        "data" => $data,
                        "status" => true
                    ], 200);
                } else {
                    return response()->json([
                        "data" => "already exist this company name !",
                        "status" => false
                    ], 400);
                }
            } else {
                return response()->json([
                    "data" => "wrong information",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

    function delete_product_name(Request $r)
    {
        try {
            $rules = [
                'product_id' => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400); //400 envalies responce
            }
            $checkingData = Product::where("product_id", $r->product_id)->where("customer_id", auth()->user()->id)->first();
            if (!empty($checkingData)) {
                $data = Product::where("product_id", $r->product_id)->delete();
                return response()->json([
                    "data" => $data,
                    "status" => true
                ], 200);
            } else {
                return response()->json([
                    "data" => "wrong information",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

    //add model

    function add_model_name(Request $r)
    {
        try {
            $rules = [
                'model_name' => 'required|string',
                'product_id' => 'required|numeric',
                'company_id' => 'required|numeric',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 401); //400 envalies responce
            }
            $checkingData = MastarModel::where("model_name", $r->model_name)->where("product_id", $r->product_id)->where("company_id", $r->company_id)->where("customer_id", auth()->user()->id)->first();
            if (empty($checkingData)) {
                $data = MastarModel::create([
                    "company_id" => $r->company_id,
                    "product_id" => $r->product_id,
                    "model_name" => $r->model_name,
                    "customer_id" => auth()->user()->id,
                    "created_by" => auth()->user()->id
                ]);
                return response()->json([
                    "data" => $data,
                    "status" => true
                ], 200);
            } else {
                return response()->json([
                    "data" => "already exist this Model name !",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function model_name()
    {
        try {
            $checkingData = MastarModel::join("company_list AS a","a.company_id","=","model.company_id")
                            ->join("procuct AS b","b.product_id","=","model.product_id")
                            ->where("model.customer_id", auth()->user()->id)->select('model.*','a.company_name','b.product_name')->get();
            return response()->json([
                "data" => $checkingData,
                "status" => false
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }


    function edit_model_name(Request $r)
    {
        try {
            $rules = [
                'model_id' => 'required|numeric',
                'model_name' => 'required|string',
                'product_id' => 'required|numeric',
                'company_id' => 'required|numeric',
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 401); //400 envalies responce
            }
            $checkingData = MastarModel::where("model_id", $r->model_id)->where("customer_id", auth()->user()->id)->first();
            if (!empty($checkingData)) {

                    $data = MastarModel::where("model_id", $r->model_id)->update([
                        "company_id" => $r->company_id,
                        "product_id" => $r->product_id,
                        "model_name" => $r->model_name,
                        "update_by" => auth()->user()->id
                    ]);
                    return response()->json([
                        "data" => $data,
                        "status" => true
                    ], 200);

            } else {
                return response()->json([
                    "data" => "wrong information",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }

    function delete_model_name(Request $r)
    {
        try {
            $rules = [
                'model_id' => 'required|numeric'
            ];
            $valaditor = Validator::make($r->all(), $rules);
            if ($valaditor->fails()) {
                return response()->json($valaditor->errors(), 400); //400 envalies responce
            }
            $checkingData = MastarModel::where("model_id", $r->model_id)->where("customer_id", auth()->user()->id)->first();
            if (!empty($checkingData)) {
                $data = MastarModel::where("model_id", $r->model_id)->delete();
                return response()->json([
                    "data" => $data,
                    "status" => true
                ], 200);
            } else {
                return response()->json([
                    "data" => "wrong information",
                    "status" => false
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json($th, 400);
        }
    }
}
