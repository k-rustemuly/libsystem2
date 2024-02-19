<?php

namespace Database\Seeders;

use App\Models\OrganizationBookStatus;
use Illuminate\Database\Seeder;

class OrganizationBookStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrganizationBookStatus::create([
            'name_kk' => 'Кітапханада',
            'name_ru' => 'В библиотеке',
        ]);

        OrganizationBookStatus::create([
            'name_kk' => 'Оқырманда',
            'name_ru' => 'У читателя',
        ]);

        OrganizationBookStatus::create([
            'name_kk' => 'Есептен шығарылды',
            'name_ru' => 'Списано',
        ]);

    }
}
