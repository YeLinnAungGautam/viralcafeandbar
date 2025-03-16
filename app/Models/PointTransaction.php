<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'order_id', 'usage_amount', 'exchange_rate', 'point', 'status'];

    protected $casts = [
        'usage_amount'  => 'float',
        'percentage'    => 'float',
        'exchange_rate' => 'float',
        'point'         => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // public function getPointAttribute($value)
    // {
    //     return number_format($value, 1);
    // }
}
