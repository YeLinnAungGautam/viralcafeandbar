<?php

namespace App\Models;

use App\Casts\JsonDecode;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use App\Http\Traits\UploadAttribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = ['name', 'description', 'type', 'active', 'product_status', 'additionals', 'has_additional'];

    const PRODUCT_TYPE = [
        'simple' => 'simple',
        'vairation' => 'vairation',
    ];

    const PRODUCT_STATUS = [
        'arrival' => 'arrival',
        'featured' => 'featured',
    ];

    const ACTIVE = 'active';

    protected $appends = ['upload'];

    protected $casts = [
        'additionals' => JsonDecode::class,
        'has_additional' => 'boolean',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->crop(50, 50)
            ->nonQueued();
    }

    public function getImagesAttribute()
    {
        $files = $this->getMedia('images');

        return $files;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes  = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'product', 'name');
            });
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class, 'product_id', 'id');
    }

    public function sku(): HasOne
    {
        return $this->hasOne(Sku::class, 'product_id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_variations');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function skuAttributeOptions()
    {
        return $this->hasManyThrough(SkuAttributeOption::class, Sku::class);
    }

    public function translates(): MorphMany
    {
        return $this->morphMany(Translate::class, 'model');
    }

    public function video()
    {
        return $this->morphOne(Video::class, 'model');
    }

    public function scopeIsActive($query)
    {
        return $query->where('active', $this::ACTIVE);
    }

    public function scopeNewArrival($query)
    {
        return $query->where('product_status', 'arrival');
    }

    public function banner()
    {
        return $this->hasOne(Banner::class);
    }


    // Boot method for the model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        // static::deleting(function ($product) {
        //     if ($product->banner) {
        //         $product->banner->delete();
        //     }
        // });
    }
}
