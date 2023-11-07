<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerMod extends Model
{
    use HasFactory;
    protected $table = "md_diller";
    protected $primaryKey = 'id';


    protected $fillable = [
        "shop_name", "contact", "adress"
    ];
}
