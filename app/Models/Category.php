<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes, LogsActivity;

    protected $fillable = ['name', 'parent_id'];

    protected $appends = [
        'upload',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->crop(150, 150)
            ->nonQueued();
    }
    public function getImagesAttribute()
    {
        $files = $this->getMedia('images');

        return $files;
    }
    public function getUploadAttribute()
    {
        $file = $this->getMedia('images')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function scopeIsParent($query)
    {
        return $query->where('parent_id', null);
    }

    public function translates(): MorphMany
    {
        return $this->morphMany(Translate::class, 'model');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes  = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'category', 'name');
            });
    }
}
