<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "td_transaction";
    protected $primaryKey = 'transaction_id';



    protected $fillable = [
         "billing_id", "payment_flag", "amount", "customer_id", "transaction_date", "created_by", "updated_by"
    ];
}
