<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MastarModel extends Model
{
    use HasFactory;

    protected $table = "model";
    protected $primaryKey = 'model_id';


    protected $fillable = [
        "company_id", "product_id", "model_name", "customer_id", "created_by", "update_by"
    ];
}
