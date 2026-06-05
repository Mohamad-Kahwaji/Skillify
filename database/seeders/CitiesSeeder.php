<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // ── المحافظات الكبرى ──────────────────────────────────────────
            ['name_ar' => 'دمشق',         'name_en' => 'Damascus',      'governorate_ar' => 'دمشق',          'governorate_en' => 'Damascus',      'latitude' => 33.5138,  'longitude' => 36.2765],
            ['name_ar' => 'حلب',          'name_en' => 'Aleppo',        'governorate_ar' => 'حلب',           'governorate_en' => 'Aleppo',        'latitude' => 36.2021,  'longitude' => 37.1343],
            ['name_ar' => 'حمص',          'name_en' => 'Homs',          'governorate_ar' => 'حمص',           'governorate_en' => 'Homs',          'latitude' => 34.7303,  'longitude' => 36.7138],
            ['name_ar' => 'حماة',         'name_en' => 'Hama',          'governorate_ar' => 'حماة',          'governorate_en' => 'Hama',          'latitude' => 35.1321,  'longitude' => 36.7560],
            ['name_ar' => 'اللاذقية',     'name_en' => 'Latakia',       'governorate_ar' => 'اللاذقية',      'governorate_en' => 'Latakia',       'latitude' => 35.5317,  'longitude' => 35.7918],
            ['name_ar' => 'طرطوس',        'name_en' => 'Tartus',        'governorate_ar' => 'طرطوس',         'governorate_en' => 'Tartus',        'latitude' => 34.8952,  'longitude' => 35.8867],
            ['name_ar' => 'درعا',         'name_en' => 'Daraa',         'governorate_ar' => 'درعا',          'governorate_en' => 'Daraa',         'latitude' => 32.6189,  'longitude' => 36.1021],
            ['name_ar' => 'دير الزور',    'name_en' => 'Deir ez-Zor',   'governorate_ar' => 'دير الزور',     'governorate_en' => 'Deir ez-Zor',   'latitude' => 35.3359,  'longitude' => 40.1406],
            ['name_ar' => 'الرقة',        'name_en' => 'Raqqa',         'governorate_ar' => 'الرقة',         'governorate_en' => 'Raqqa',         'latitude' => 35.9500,  'longitude' => 39.0167],
            ['name_ar' => 'إدلب',         'name_en' => 'Idlib',         'governorate_ar' => 'إدلب',          'governorate_en' => 'Idlib',         'latitude' => 35.9325,  'longitude' => 36.6342],
            ['name_ar' => 'القامشلي',     'name_en' => 'Qamishli',      'governorate_ar' => 'الحسكة',        'governorate_en' => 'Hasaka',        'latitude' => 37.0511,  'longitude' => 41.2274],
            ['name_ar' => 'السويداء',     'name_en' => 'As-Suwayda',    'governorate_ar' => 'السويداء',      'governorate_en' => 'As-Suwayda',    'latitude' => 32.7089,  'longitude' => 36.5661],
            ['name_ar' => 'الحسكة',       'name_en' => 'Al-Hasakah',    'governorate_ar' => 'الحسكة',        'governorate_en' => 'Hasaka',        'latitude' => 36.5000,  'longitude' => 40.7500],
            ['name_ar' => 'القنيطرة',     'name_en' => 'Quneitra',      'governorate_ar' => 'القنيطرة',      'governorate_en' => 'Quneitra',      'latitude' => 33.1263,  'longitude' => 35.8243],

            // ── مدن ثانوية ────────────────────────────────────────────────
            ['name_ar' => 'بانياس',       'name_en' => 'Baniyas',       'governorate_ar' => 'طرطوس',         'governorate_en' => 'Tartus',        'latitude' => 35.1836,  'longitude' => 35.9394],
            ['name_ar' => 'جبلة',         'name_en' => 'Jableh',        'governorate_ar' => 'اللاذقية',      'governorate_en' => 'Latakia',       'latitude' => 35.3636,  'longitude' => 35.9222],
            ['name_ar' => 'صافيتا',       'name_en' => 'Safita',        'governorate_ar' => 'طرطوس',         'governorate_en' => 'Tartus',        'latitude' => 34.8167,  'longitude' => 36.1167],
            ['name_ar' => 'مصياف',        'name_en' => 'Masyaf',        'governorate_ar' => 'حماة',          'governorate_en' => 'Hama',          'latitude' => 35.0706,  'longitude' => 36.3411],
            ['name_ar' => 'تدمر',         'name_en' => 'Palmyra',       'governorate_ar' => 'حمص',           'governorate_en' => 'Homs',          'latitude' => 34.5538,  'longitude' => 38.2692],
            ['name_ar' => 'منبج',         'name_en' => 'Manbij',        'governorate_ar' => 'حلب',           'governorate_en' => 'Aleppo',        'latitude' => 36.5109,  'longitude' => 37.9451],
            ['name_ar' => 'القصير',       'name_en' => 'Al-Qusayr',     'governorate_ar' => 'حمص',           'governorate_en' => 'Homs',          'latitude' => 34.5083,  'longitude' => 36.5778],
            ['name_ar' => 'السلمية',      'name_en' => 'As-Salamiyah',  'governorate_ar' => 'حماة',          'governorate_en' => 'Hama',          'latitude' => 35.0119,  'longitude' => 37.0547],
            ['name_ar' => 'تل كلخ',       'name_en' => 'Tall Kalakh',   'governorate_ar' => 'حمص',           'governorate_en' => 'Homs',          'latitude' => 34.6708,  'longitude' => 36.2602],
            ['name_ar' => 'يبرود',        'name_en' => 'Yabroud',       'governorate_ar' => 'ريف دمشق',      'governorate_en' => 'Rural Damascus','latitude' => 33.9706,  'longitude' => 36.6581],
            ['name_ar' => 'الزبداني',     'name_en' => 'Az-Zabadani',   'governorate_ar' => 'ريف دمشق',      'governorate_en' => 'Rural Damascus','latitude' => 33.7239,  'longitude' => 36.0958],
            ['name_ar' => 'دوما',         'name_en' => 'Douma',         'governorate_ar' => 'ريف دمشق',      'governorate_en' => 'Rural Damascus','latitude' => 33.5725,  'longitude' => 36.3994],
            ['name_ar' => 'عين العرب',    'name_en' => 'Kobani',        'governorate_ar' => 'حلب',           'governorate_en' => 'Aleppo',        'latitude' => 36.8928,  'longitude' => 38.3564],
            ['name_ar' => 'الباب',        'name_en' => 'Al-Bab',        'governorate_ar' => 'حلب',           'governorate_en' => 'Aleppo',        'latitude' => 36.3725,  'longitude' => 37.5178],
            ['name_ar' => 'عفرين',        'name_en' => 'Afrin',         'governorate_ar' => 'حلب',           'governorate_en' => 'Aleppo',        'latitude' => 36.5128,  'longitude' => 36.8697],
            ['name_ar' => 'إعزاز',        'name_en' => 'Azaz',          'governorate_ar' => 'حلب',           'governorate_en' => 'Aleppo',        'latitude' => 36.5869,  'longitude' => 37.0503],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name_ar' => $city['name_ar']],
                $city
            );
        }
    }
}
