<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Buildit\Helpers\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'iin',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->singleFile();
    }

    public function setPhoneNumberFormattedAttribute($value)
    {
        $this->attributes['phone_number'] = $value ? PhoneNumber::format($value) : null;
    }

    public function getPhoneNumberFormattedAttribute()
    {
        return isset($this->attributes['phone_number']) ? PhoneNumber::unformat($this->attributes['phone_number']) : null;
    }

    public function getAvatarAttribute()
    {
        $avatar = $this->getFirstMediaUrl('avatar');
        return $avatar == "" ? null : $avatar;
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }
}
