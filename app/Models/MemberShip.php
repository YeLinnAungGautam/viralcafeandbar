<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class MemberShip extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['name', 'rule', 'min_point', 'max_point', 'percentage', 'status'];


    protected $casts = [
        'min_point' => 'float',
        'max_point' => 'float',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'membership', 'name');
            });
    }
}
