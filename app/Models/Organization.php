<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Organization extends LocalizableModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'bin',
        'name_kk',
        'name_ru',
        'kato',
        'organization_type_id',
        'legal_address_kk',
        'legal_address_ru',
        'short_name_kk',
        'short_name_ru',
        'short_type_kk',
        'short_type_ru',
    ];

    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = [
        'name',
        'legal_address',
        'short_name',
        'short_type',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->useFallbackUrl(Storage::url('images/organization.jpg'))
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->singleFile();
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    public function organizationType(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class);
    }

    public function getImageAttribute()
    {
        return 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/39/Hibbing_High_School_2014.jpg/1200px-Hibbing_High_School_2014.jpg';
        return $this->getFirstMediaUrl('image');
    }

    public function books(): HasManyThrough
    {
        return $this->hasManyThrough(Book::class, OrganizationBook::class, 'organization_id', 'id', 'id', 'book_id');
    }

    public function receivedBooks(): HasManyThrough
    {
        return $this->hasManyThrough(Book::class, ReceivedBook::class, 'organization_id', 'id', 'id', 'book_id');
    }

    public function readers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, OrganizationReader::class, 'organization_id', 'id', 'id', 'user_id');
    }
}
