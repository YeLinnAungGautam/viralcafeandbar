<?php

namespace App\Models;

use App\Models\Language;
use App\Casts\JsonDecode;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Translate extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'model_id', 'model_type', 'language_id'];

    protected $casts = [
        'value' => JsonDecode::class,
    ];

    public function getValueAttributs($value)
    {
        return json_decode($value, true);
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
