<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessInfo extends Model
{
    use HasFactory;

    protected $table = "business_info";
    protected $primaryKey = 'my_string_key';


    protected $fillable = [
        "business_id", "business_name", "business_short_name", "adress", "business_mobile", "business_email", "business_location","business_type", "created_by", "update_by", "deleted_flag", "deleted_by", "deleted_at"
    ];
}
