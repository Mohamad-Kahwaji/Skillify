<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\IdentityVerification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class IdentityVerificationSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('identity');

        $adminId = Admin::first()?->id;
        $users   = User::take(10)->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping identity verifications.');
            return;
        }

        $records = [
            [
                'slug'           => 'hanin_hassan',
                'full_name'      => 'حنين الحسن',
                'id_number'      => '010503045612',
                'id_type'        => 'national_id',
                'status'         => 'approved',
                'match_score'    => 94.5,
                'extracted_data' => [
                    'extracted'          => ['full_name' => 'حنين الحسن', 'id_number' => '010503045612', 'expiry_date' => '2029-08-15', 'gender' => 'أنثى'],
                    'name_match'         => true,
                    'id_match'           => true,
                    'document_authentic' => true,
                    'match_score'        => 94,
                    'verdict'            => 'approved',
                    'notes'              => 'جميع البيانات متطابقة. الوثيقة أصيلة.',
                ],
            ],
            [
                'slug'           => 'mohammad_ali',
                'full_name'      => 'محمد العلي',
                'id_number'      => '020401078934',
                'id_type'        => 'national_id',
                'status'         => 'approved',
                'match_score'    => 88.0,
                'extracted_data' => [
                    'extracted'          => ['full_name' => 'محمد العلي', 'id_number' => '020401078934', 'expiry_date' => '2027-03-20', 'gender' => 'ذكر'],
                    'name_match'         => true,
                    'id_match'           => true,
                    'document_authentic' => true,
                    'match_score'        => 88,
                    'verdict'            => 'approved',
                    'notes'              => 'البيانات متطابقة ونسبة التطابق عالية. الوثيقة سليمة.',
                ],
            ],
            [
                'slug'           => 'omar_mohammad',
                'full_name'      => 'عمر المحمد',
                'id_number'      => 'P12938745',
                'id_type'        => 'passport',
                'status'         => 'approved',
                'match_score'    => 91.0,
                'extracted_data' => [
                    'extracted'          => ['full_name' => 'عمر المحمد', 'id_number' => 'P12938745', 'expiry_date' => '2030-11-01', 'gender' => 'ذكر'],
                    'name_match'         => true,
                    'id_match'           => true,
                    'document_authentic' => true,
                    'match_score'        => 91,
                    'verdict'            => 'approved',
                    'notes'              => 'جواز سفر صالح. جميع المعلومات متطابقة مع بيانات المستخدم.',
                ],
            ],
            [
                'slug'           => 'ali_ahmad',
                'full_name'      => 'علي الأحمد',
                'id_number'      => '030204056712',
                'id_type'        => 'national_id',
                'status'         => 'pending',
                'match_score'    => null,
                'extracted_data' => null,
            ],
            [
                'slug'           => 'khalid_khateeb',
                'full_name'      => 'خالد الخطيب',
                'id_number'      => '040106089023',
                'id_type'        => 'national_id',
                'status'         => 'pending',
                'match_score'    => null,
                'extracted_data' => null,
            ],
            [
                'slug'           => 'sara_zahrani',
                'full_name'      => 'سارة الزهراني',
                'id_number'      => '050307034561',
                'id_type'        => 'national_id',
                'status'         => 'rejected',
                'match_score'    => 32.0,
                'extracted_data' => [
                    'extracted'          => ['full_name' => 'سارة الزهر', 'id_number' => '050307034561', 'expiry_date' => null, 'gender' => 'أنثى'],
                    'name_match'         => false,
                    'id_match'           => true,
                    'document_authentic' => false,
                    'match_score'        => 32,
                    'verdict'            => 'rejected',
                    'notes'              => 'الاسم غير متطابق. يُشك في أصالة الوثيقة.',
                ],
            ],
            [
                'slug'           => 'yousef_husayni',
                'full_name'      => 'يوسف الحسيني',
                'id_number'      => '060205067890',
                'id_type'        => 'national_id',
                'status'         => 'approved',
                'match_score'    => 96.0,
                'extracted_data' => [
                    'extracted'          => ['full_name' => 'يوسف الحسيني', 'id_number' => '060205067890', 'expiry_date' => '2028-06-30', 'gender' => 'ذكر'],
                    'name_match'         => true,
                    'id_match'           => true,
                    'document_authentic' => true,
                    'match_score'        => 96,
                    'verdict'            => 'approved',
                    'notes'              => 'توثيق ممتاز. جميع البيانات متطابقة بدقة عالية جداً.',
                ],
            ],
            [
                'slug'           => 'rana_omar',
                'full_name'      => 'رنا العمر',
                'id_number'      => '070108023456',
                'id_type'        => 'national_id',
                'status'         => 'pending',
                'match_score'    => null,
                'extracted_data' => null,
            ],
        ];

        $reviewedAt = now()->subDays(2);

        foreach ($records as $i => $record) {
            $user = $users->get($i);
            if (!$user) break;

            if (IdentityVerification::where('user_id', $user->id)->exists()) continue;

            $slug       = $record['slug'];
            $frontImage = "identity/id_front_{$slug}.png";
            $backImage  = "identity/id_back_{$slug}.png";

            // Fallback to placeholder if image generation hasn't run yet
            if (!Storage::disk('public')->exists($frontImage)) {
                $placeholder = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQI12NgAAIABQAABjE+ibYAAAAASUVORK5CYII=');
                Storage::disk('public')->put($frontImage, $placeholder);
                Storage::disk('public')->put($backImage,  $placeholder);
            }

            IdentityVerification::create([
                'user_id'          => $user->id,
                'full_name'        => $record['full_name'],
                'id_number'        => $record['id_number'],
                'id_type'          => $record['id_type'],
                'front_image'      => $frontImage,
                'back_image'       => $backImage,
                'status'           => $record['status'],
                'match_score'      => $record['match_score'],
                'extracted_data'   => $record['extracted_data']
                    ? json_encode($record['extracted_data'], JSON_UNESCAPED_UNICODE)
                    : null,
                'reviewed_by'      => in_array($record['status'], ['approved', 'rejected']) ? $adminId : null,
                'reviewed_at'      => in_array($record['status'], ['approved', 'rejected']) ? $reviewedAt : null,
                'rejection_reason' => $record['status'] === 'rejected'
                    ? 'الاسم لا يتطابق مع بيانات الوثيقة. يرجى إعادة التقديم بوثيقة واضحة.'
                    : null,
            ]);
        }

        $this->command->info('Identity verifications seeded: ' . count($records) . ' records.');
    }
}
