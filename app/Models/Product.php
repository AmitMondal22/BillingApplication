<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "procuct";
    protected $primaryKey = 'product_id';


    protected $fillable = [
        "product_name", "customer_id", "create_by", "updated_by"
    ];
}
