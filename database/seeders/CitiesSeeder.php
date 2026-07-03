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
            ['name' => 'دمشق',         'governorate' => 'دمشق',          'latitude' => 33.5138,  'longitude' => 36.2765],
            ['name' => 'حلب',          'governorate' => 'حلب',           'latitude' => 36.2021,  'longitude' => 37.1343],
            ['name' => 'حمص',          'governorate' => 'حمص',           'latitude' => 34.7303,  'longitude' => 36.7138],
            ['name' => 'حماة',         'governorate' => 'حماة',          'latitude' => 35.1321,  'longitude' => 36.7560],
            ['name' => 'اللاذقية',     'governorate' => 'اللاذقية',      'latitude' => 35.5317,  'longitude' => 35.7918],
            ['name' => 'طرطوس',        'governorate' => 'طرطوس',         'latitude' => 34.8952,  'longitude' => 35.8867],
            ['name' => 'درعا',         'governorate' => 'درعا',          'latitude' => 32.6189,  'longitude' => 36.1021],
            ['name' => 'دير الزور',    'governorate' => 'دير الزور',     'latitude' => 35.3359,  'longitude' => 40.1406],
            ['name' => 'الرقة',        'governorate' => 'الرقة',         'latitude' => 35.9500,  'longitude' => 39.0167],
            ['name' => 'إدلب',         'governorate' => 'إدلب',          'latitude' => 35.9325,  'longitude' => 36.6342],
            ['name' => 'القامشلي',     'governorate' => 'الحسكة',        'latitude' => 37.0511,  'longitude' => 41.2274],
            ['name' => 'السويداء',     'governorate' => 'السويداء',      'latitude' => 32.7089,  'longitude' => 36.5661],
            ['name' => 'الحسكة',       'governorate' => 'الحسكة',        'latitude' => 36.5000,  'longitude' => 40.7500],
            ['name' => 'القنيطرة',     'governorate' => 'القنيطرة',      'latitude' => 33.1263,  'longitude' => 35.8243],

            // ── مدن ثانوية ────────────────────────────────────────────────
            ['name' => 'بانياس',       'governorate' => 'طرطوس',         'latitude' => 35.1836,  'longitude' => 35.9394],
            ['name' => 'جبلة',         'governorate' => 'اللاذقية',      'latitude' => 35.3636,  'longitude' => 35.9222],
            ['name' => 'صافيتا',       'governorate' => 'طرطوس',         'latitude' => 34.8167,  'longitude' => 36.1167],
            ['name' => 'مصياف',        'governorate' => 'حماة',          'latitude' => 35.0706,  'longitude' => 36.3411],
            ['name' => 'تدمر',         'governorate' => 'حمص',           'latitude' => 34.5538,  'longitude' => 38.2692],
            ['name' => 'منبج',         'governorate' => 'حلب',           'latitude' => 36.5109,  'longitude' => 37.9451],
            ['name' => 'القصير',       'governorate' => 'حمص',           'latitude' => 34.5083,  'longitude' => 36.5778],
            ['name' => 'السلمية',      'governorate' => 'حماة',          'latitude' => 35.0119,  'longitude' => 37.0547],
            ['name' => 'تل كلخ',       'governorate' => 'حمص',           'latitude' => 34.6708,  'longitude' => 36.2602],
            ['name' => 'يبرود',        'governorate' => 'ريف دمشق',      'latitude' => 33.9706,  'longitude' => 36.6581],
            ['name' => 'الزبداني',     'governorate' => 'ريف دمشق',      'latitude' => 33.7239,  'longitude' => 36.0958],
            ['name' => 'دوما',         'governorate' => 'ريف دمشق',      'latitude' => 33.5725,  'longitude' => 36.3994],
            ['name' => 'عين العرب',    'governorate' => 'حلب',           'latitude' => 36.8928,  'longitude' => 38.3564],
            ['name' => 'الباب',        'governorate' => 'حلب',           'latitude' => 36.3725,  'longitude' => 37.5178],
            ['name' => 'عفرين',        'governorate' => 'حلب',           'latitude' => 36.5128,  'longitude' => 36.8697],
            ['name' => 'إعزاز',        'governorate' => 'حلب',           'latitude' => 36.5869,  'longitude' => 37.0503],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name' => $city['name']],
                $city
            );
        }
    }
}
