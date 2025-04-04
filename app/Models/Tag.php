<?php

namespace App\Models;


use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $fillable = ['name', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
                $changedAttributes = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'tag', 'name');
            });
    }
}
