<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeedeers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SuperAdmin::firstOrCreate(
            ['email' => 'mbk47@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name'  => 'MBK',
                'password'   => 'password',
            ]
        );
    }
}
