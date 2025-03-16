<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = ['order_no', 'total_tax', 'subtotal', 'total_price', 'total_discount', 'order_status', 'membership_discount', 'membership_discount_amount', 'payment_method', 'remark', 'customer_id', 'payment_status', 'internal_note', 'earn'];

    protected $casts = [
        'total_tax'                  => 'float',
        'subtotal'                   => 'float',
        'total_price'                => 'float',
        'total_discount'             => 'float',
        'membership_discount'        => 'float',
        'membership_discount_amount' => 'float',
        'earn'                       => 'float',
        'payment_method'             => 'float',
    ];

    const ORDER_STATUS = [
        'pending'   => 'pending',
        'confirmed' => 'confirmed',
        'cancel'    => 'cancel',
    ];

    const PAYMENT_STATUS = [
        'paid'   => 'paid',
        'unpaid' => 'unpaid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes  = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'order', 'order_no');
            });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id')->with('product');
    }

    public function orderCustomer(): HasOne
    {
        return $this->hasOne(OrderCustomer::class, 'order_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->with(['customer', 'payment']);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'order_id', 'id')->with(['customer']);
    }

    public function lastPointTransaction(): HasOne
    {
        return $this->hasOne(PointTransaction::class, 'order_id', 'id')->latest();
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_method', 'id');
    }
}
