<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Promotion extends EloquentModel implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, LogsActivity;
    protected $fillable = ['name', 'description', 'short_description', 'status', 'product_id', 'category_id'];

    protected $appends = ['upload'];
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 50, 50)
            ->nonQueued();
    }

    public function getImagesAttribute()
    {
        $files = $this->getMedia('images');

        return $files;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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

    public function translates(): MorphMany
    {
        return $this->morphMany(Translate::class, 'model');
    }


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'promotion_tags', 'promotion_id', 'tag_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($promotion) {
            $promotion->slug = Str::slug($promotion->name);
        });

        static::updating(function ($promotion) {
            $promotion->slug = Str::slug($promotion->name);
        });
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes  = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'promotion', 'name');
            });
    }
}
