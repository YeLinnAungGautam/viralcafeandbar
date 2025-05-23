<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionTag extends Model
{
    use HasFactory;
    protected $fillable = ['promotion_id', 'tag_id'];
}
