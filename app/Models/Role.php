<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends LocalizableModel
{
    use HasFactory;

    /**
     * Супер Админ
     */
    const SUPER_ADMIN = 1;

    /**
     * Директор
     */
    const DIRECTOR = 2;

    /**
     * Библиотекарь
     */
    const LIBRARIAN = 3;

    /**
     * Учитель
     */
    const TEACHER = 4;

    /**
     * Читатель
     */
    const READER = 5;

    protected $localizable = ['name'];
}
