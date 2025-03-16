<?php

namespace App\Models;

use App\Casts\JsonDecode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group'];

    public function scopeGetValueByKey($query, $key)
    {
        $data = $query->get()
            ->pluck('value', 'key')
            ->toArray();

        return $data[$key] ?? '';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes  = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'setting', 'value');
            });
    }
}
