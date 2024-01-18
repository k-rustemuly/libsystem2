<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookStorageType extends LocalizableModel
{
    use HasFactory;

    /**
     * @var BASIC Основной фонд
     */
    const BASIC = 1;

    /**
     * @var GENERAL Общий фонд
     */
    const GENERAL = 2;

    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = ['name'];
}
