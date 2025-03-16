<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDeviceToken extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'token', 'status'];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
