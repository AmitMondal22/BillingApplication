<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyName extends Model
{
    use HasFactory;

    protected $table = "company_list";
    protected $primaryKey = 'company_id';


    protected $fillable = [
        "company_name", "customer_id", "created_by", "updated_by"
    ];
}
