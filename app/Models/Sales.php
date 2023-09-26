<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = "td_sales";
    protected $primaryKey = 'sales_id';



    protected $fillable = [
        "billing_id", "stock_id", "price", "cgst", "sgst", "payment_flag", "billingdate", "created_by", "updated_by"
    ];
}
