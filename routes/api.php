<?php

use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\MastarController;
use App\Http\Controllers\admin\StoreStock;
use App\Http\Controllers\user\SellersLabel;
use App\Http\Controllers\user\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[User::class,'create_account']);
Route::post('/otp-validation',[User::class,'otp_validation']);
Route::post('/resend-otp',[User::class,'resend_otp']);
Route::post('/change-password',[User::class,'new_password']);
Route::post('/login',[User::class,'login']);






Route::middleware('auth:sanctum','ability:U')->group(function(){
    Route::get('view-auth',[User::class,'getview']);

    Route::post('/add_sellers',[SellersLabel::class,'createSellers']);
    //===================logout=========================
    Route::post('/logout',[User::class,'logout']);
});


Route::middleware('auth:sanctum','ability:A')->group(function(){
    Route::name('mastar.')->prefix('master')->group(function() {
        Route::post('/add_company_name',[MastarController::class,'add_company_name']);
        Route::get('/company_name',[MastarController::class,'company_name']);
        Route::post('/edit_company_name',[MastarController::class,'edit_company_name']);
        Route::post('/delete_company_name',[MastarController::class,'delete_company_name']);


        Route::post('/add_product_name',[MastarController::class,'add_product_name']);
        Route::get('/product_name',[MastarController::class,'product_name']);
        Route::post('/edit_product_name',[MastarController::class,'edit_product_name']);
        Route::post('/delete_product_name',[MastarController::class,'delete_product_name']);


        Route::post('/add_model_name',[MastarController::class,'add_model_name']);
        Route::get('/model_name',[MastarController::class,'model_name']);
        Route::post('/edit_model_name',[MastarController::class,'edit_model_name']);
        Route::post('/delete_model_name',[MastarController::class,'delete_model_name']);
    });

    Route::name('stock.')->prefix('stock')->group(function() {
        Route::post('/in',[StoreStock::class,'stockIn']);
        Route::get('/stock_product',[StoreStock::class,'stock_product']);
        Route::post('/edit',[StoreStock::class,'edit_stockIn']);
        Route::post('/delete',[StoreStock::class,'delete_stock']);
        Route::post('/find_product',[StoreStock::class,'find_product']);
    });

    Route::name('billing.')->prefix('billing')->group(function() {
        Route::post('/new',[StoreStock::class,'billing']);
    });

    Route::name('customer.')->prefix('customer')->group(function() {
        Route::post('/serch_customer_mobile',[CustomerController
        ::class,'mycustomer']);

    });
});
