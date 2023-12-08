<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Book extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('cover')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->singleFile()
            ->registerMediaConversions(function () {
                $this
                    ->addMediaConversion('small')
                    ->width(50)
                    ->height(50);
            });

        $this
            ->addMediaCollection('file')
            ->acceptsMimeTypes(['application/pdf', 'application/epub+zip', ])
            ->singleFile();

    }

    public function publishingHouse(): BelongsTo
    {
        return $this->belongsTo(PublishingHouse::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function binding(): BelongsTo
    {
        return $this->belongsTo(Binding::class);
    }

    public function getIsbnNameAttribute()
    {
        return trans('moonshine::ui.resource.book_isbn_name', [
            'name' => $this->name,
            'isbn' => $this->isbn
        ]);
    }

}
