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

class TagLine extends EloquentModel implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, LogsActivity;

    protected $guarded = [];

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
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_line_tags', 'tag_line_id', 'tag_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($tagline) {
    //         $tagline->slug = Str::slug($tagline->name);
    //     });

    //     static::updating(function ($tagline) {
    //         $tagline->slug = Str::slug($tagline->name);
    //     });
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes  = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'tag line', 'name');
            });
    }
}
