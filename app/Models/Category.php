<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends LocalizableModel
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name_kk',
        'name_ru'
    ];

    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = ['name'];
}
