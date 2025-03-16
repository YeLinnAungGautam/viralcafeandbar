<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMeta extends Model
{
    use HasFactory;

    public $table = 'customer_meta';

    protected $fillable = ['customer_id', 'meta_key', 'meta_value'];
}
