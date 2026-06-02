<?php

namespace Database\Seeders;

use App\Models\ActiveTypebusiness;
use Illuminate\Database\Seeder;

class ActiveTypebusinessSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name_en' => 'Profession', 'name_ar' => 'مهنة'],
            ['name_en' => 'Craft',      'name_ar' => 'حرفة'],
            ['name_en' => 'Workshop',   'name_ar' => 'معمل'],
            ['name_en' => 'Company',    'name_ar' => 'شركة'],
        ];

        foreach ($types as $type) {
            ActiveTypebusiness::firstOrCreate(
                ['name_en' => $type['name_en']],
                ['name_ar' => $type['name_ar']]
            );
        }
    }
}
