<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Localization extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $fillable = [
        "key",
        "value",
        "language_id",
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes  = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'localization', 'key');
            });
    }

    public function scopeGetValueByKey($query, $key, $language_ids)
    {
        return $query->where('key', $key)
            ->whereIn('language_id', $language_ids)
            ->get();
    }
}
