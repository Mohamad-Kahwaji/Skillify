<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CitiesSeeder::class,             // مدن سورية
            ActiveTypebusinessSeeder::class, // مهنة / حرفة / معمل / شركة
            ActiveTypeSeeders::class,        // أنواع المنشورات
            CategorySeedeers::class,         // التصنيفات
            SubcategorySeedeers::class,      // التصنيفات الفرعية
            RolesAndPermissionsSeeder::class,// الأدوار والصلاحيات
            SuperAdminSeedeers::class,       // السوبر أدمن
            UserSeedeers::class,             // المستخدمون + الأعمال + المنشورات
            ServiceSeedeers::class,          // الخدمات
            RequestServiceSeedeers::class,   // طلبات الخدمة
        ]);
    }
}
