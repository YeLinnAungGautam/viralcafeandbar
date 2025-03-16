<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Transaction extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $casts = [
        'payment_method' => 'float',
        'amount'         => 'float',
    ];

    protected $fillable = [
        'order_id',
        'customer_id',
        'amount',
        'payment_method',
        'payment_status',
        'payment_date',
        'note',
    ];

    protected $appends = [
        'upload',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

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
        $file = $this->getMedia('images')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_method', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
