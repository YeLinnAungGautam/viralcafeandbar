<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'attribute_id'];

    public function skuAttributeOption()
    {
        return $this->belongsTo(SkuAttributeOption::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
