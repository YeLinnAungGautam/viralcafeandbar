<?php

namespace App\Models;

use App\Casts\Image;
use App\Casts\Video as CastsVideo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['model_id', 'model_type', 'video_link', 'image_link'];

    protected $casts = [
        'video_link' => CastsVideo::class,
        'image_link' => Image::class,
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
