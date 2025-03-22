<?php

namespace App\Models;

use App\Http\Traits\UploadAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Sku extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    const OUT_OF_STOCK = 'out_stock';
    const IN_STOCK     = 'in_stock';

    protected $fillable = [
        'code',
        'stock',
        'stock_status',
        'discount_schedule',
        'discount_end_date',
        'discount_start_date',
        'weight',
        'product_id',
        'discount',
        'discount_type',
        'original_price',
        'sale_price',
        'expense',
    ];

    protected $casts = [
        'original_price'    => 'float',
        'sale_price'        => 'float',
        'discount_schedule' => 'boolean',
        'expense'           => 'float',
    ];

    protected $appends = [
        'upload',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function skuAttriubuteOptions()
    {
        return $this->hasMany(SkuAttributeOption::class, 'sku_id', 'id');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->crop(50, 50)
            ->nonQueued();

    }

    public function getUploadAttribute()
    {
        $files = $this->getMedia('images');
        if ($files) {
            foreach ($files as $key => $file) {
                $file->url       = $file->getUrl();
                $file->thumbnail = $file->getUrl('thumb');
                $file->preview   = $file->getUrl('preview');
            }
        }

        return $files;
    }

    public function getImagesAttribute()
    {
        $files = $this->getMedia('images');

        return $files;
    }

    public function scopeAvaliable($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

}
