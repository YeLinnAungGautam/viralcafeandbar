<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentAccount extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $fillable = ['account_name', 'account_number', 'payment_info_id', 'status'];

    public function banks()
    {
        return $this->belongsTo(PaymentInfo::class, 'payment_info_id');
    }
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'payment account', 'account_name');
            });
    }
}
