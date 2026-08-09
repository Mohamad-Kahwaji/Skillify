<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['id_number' => '1100000001', 'first_name' => 'كريم',  'last_name' => 'العبدالله', 'email' => 'admin@skillify.sy',     'phone' => '0991000001', 'role' => 'admin'],
            ['id_number' => '1100000002', 'first_name' => 'لينا',  'last_name' => 'حمدان',     'email' => 'moderator@skillify.sy', 'phone' => '0991000002', 'role' => 'content_moderator'],
            ['id_number' => '1100000003', 'first_name' => 'يزن',   'last_name' => 'الشامي',    'email' => 'verifier@skillify.sy',  'phone' => '0991000003', 'role' => 'verifier'],
        ];

        foreach ($admins as $data) {
            $admin = Admin::firstOrCreate(
                ['email' => $data['email']],
                [
                    'id_number' => $data['id_number'],
                    'first_name' => $data['first_name'],
                    'last_name'  => $data['last_name'],
                    'password'   => Hash::make('password'),
                    'phone'      => $data['phone'],
                    'role'       => $data['role'],
                    'status'     => 'active',
                ]
            );
            $admin->syncRoles([$data['role']]);
        }

        $this->command->info('Admins seeded: ' . count($admins) . ' accounts.');
    }
}
