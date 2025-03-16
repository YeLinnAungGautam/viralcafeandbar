<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Language extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'code', 'active', 'is_default'];

    public function scopeActive(Builder $query): void
    {
        $query->where('active', 'active');
    }

    public function scopeDefault(Builder $query): void
    {
        $query->where('is_default', 1);
    }

    public function translates()
    {
        return $this->hasMany(Translate::class)->with('language');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'language', 'name');
            });
    }
}
