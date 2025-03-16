<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkuAttributeOption extends Model
{
    use HasFactory;

    protected $fillable = ['sku_id', 'attribute_option_id'];

    public $with = ['attributeOption'];

    public function attributeOption()
    {
        return $this->hasOne(AttributeOption::class, 'id', 'attribute_option_id');
    }
}
