<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMemberShip extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = ['point_history', 'point', 'percentage', 'customer_id', 'member_ship_id', 'is_percentage_default'];

    protected $casts = [
        'point'                 => 'float',
        // 'percentage'    => 'interger',
        'point_history'         => 'float',
        'is_percentage_default' => 'boolean',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'member_ship_id', 'id');
    }
}
