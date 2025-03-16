<?php

namespace App\Models;

use App\Casts\Customer\Image;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes, Notifiable, LogsActivity;
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'password',
        'status',
        'image',
        'is_notification',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $casts = [
        'image'           => Image::class,
        'is_notification' => 'boolean',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function wishlists()
    {
        return $this->belongsToMany(Product::class, 'wishlists', 'customer_id', 'product_id');
    }

    public function customerMemberShip()
    {
        return $this->hasOne(CustomerMemberShip::class);
    }

    public function customerMeta()
    {
        return $this->hasMany(CustomerMeta::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function scopeNotification($query)
    {
        return $query->where('is_notification', true);
    }

    public function fcmToken()
    {
        return $this->hasMany(CustomerDeviceToken::class, 'customer_id', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->setDescriptionForEvent(function (string $eventName) {
                $changedAttributes = $this->getDirty();
                $originalAttributes = $this->getOriginal();
                return getDescription($eventName, $originalAttributes, $changedAttributes, 'customer', 'username');
            });
    }
}
