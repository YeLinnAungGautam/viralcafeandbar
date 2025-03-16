<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCustomer extends Model
{
    use HasFactory;

    public $fillable = ['order_id', 'first_name', 'last_name', 'country', 'customer_id', 'email', 'phone', 'address'];
}
