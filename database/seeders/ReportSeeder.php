<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $reports = [
            [
                'post_title'    => 'مبروك: نالت شركتنا جائزة التميز في الجودة 2025',
                'reporter_email' => 'user27@hirfa.sy',
                'reason'        => 'منشور مكرر، نفس المحتوى تم نشره أكثر من مرة من حسابات مختلفة.',
            ],
            [
                'post_title'    => 'أقدم خدمات صيانة كهربائية منزلية بأسعار مناسبة',
                'reporter_email' => 'user16@hirfa.sy',
                'reason'        => 'الأسعار المذكورة في المنشور غير حقيقية ومختلفة تماماً عمّا طُلب مني عند التواصل.',
            ],
            [
                'post_title'    => 'تدريب على المحاسبة وإدارة الأعمال',
                'reporter_email' => 'user8@hirfa.sy',
                'reason'        => 'محتوى ترويجي غير مرتبط بمجال المنصة ويطلب التواصل عبر رقم خارجي مباشرة.',
            ],
        ];

        foreach ($reports as $data) {
            $post = Post::where('title', $data['post_title'])->first();
            $user = User::where('email', $data['reporter_email'])->first();

            if (!$post || !$user) continue;

            Report::firstOrCreate([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ], [
                'reason' => $data['reason'],
            ]);
        }

        $this->command->info('Reports seeded: ' . count($reports) . ' records.');
    }
}
