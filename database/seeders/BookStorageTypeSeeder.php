<?php

namespace Database\Seeders;

use App\Models\BookStorageType;
use Illuminate\Database\Seeder;

class BookStorageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookStorageType::create([
            'name_kk' => 'Негізгі қор',
            'name_ru' => 'Основной фонд',
        ]);
        BookStorageType::create([
            'name_kk' => 'Жалпы қор',
            'name_ru' => 'Общий фонд',
        ]);
    }
}
