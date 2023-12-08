<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Binding extends LocalizableModel
{
    use HasFactory;

    protected $fillable = [
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
