<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorIn extends Model
{
    use HasFactory;
    protected $table = "td_product_store";
    protected $primaryKey = 'product_store_id';


    protected $fillable = [
        "model_id", "serial_number", "purchase_rate", "sales_rate", "purchase_by", "purchase_date", "warranty_expired", "sels_warranty", "exchange_product_id", "cgst_p", "sgst_p", "sales_flags", "customer_id", "created_by", "update_by"
    ];
}
