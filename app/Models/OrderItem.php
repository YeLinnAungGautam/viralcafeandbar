<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'sku_id', 'product_name', 'sku_name', 'order_id', 'qty', 'unit_price', 'discount_amount', 'total_price', 'tax_price'];

    protected $casts = [
        'unit_price'      => 'float',
        'total_price'     => 'float',
        'discount_amount' => 'float',
        'tax_price'       => 'float',
    ];

    protected $appends = ['original_price'];

    public function getOriginalPriceAttribute()
    {
        return $this->unit_price + ($this->discount_amount / $this->qty);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
