<?php

namespace Database\Seeders;

use App\Models\ActiveTypebusiness;
use Illuminate\Database\Seeder;

class ActiveTypebusinessSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['مهنة', 'حرفة', 'معمل', 'شركة'];

        foreach ($types as $name) {
            ActiveTypebusiness::firstOrCreate(['name' => $name]);
        }
    }
}
