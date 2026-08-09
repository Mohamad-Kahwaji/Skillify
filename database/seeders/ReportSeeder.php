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
                'post_title' => 'مبروك: نالت شركتنا جائزة التميز في الجودة 2025',
                'reason'     => 'منشور مكرر، نفس المحتوى تم نشره أكثر من مرة من حسابات مختلفة.',
            ],
            [
                'post_title' => 'أقدم خدمات صيانة كهربائية منزلية بأسعار مناسبة',
                'reason'     => 'الأسعار المذكورة في المنشور غير حقيقية ومختلفة تماماً عمّا طُلب مني عند التواصل.',
            ],
            [
                'post_title' => 'تدريب على المحاسبة وإدارة الأعمال',
                'reason'     => 'محتوى ترويجي غير مرتبط بمجال المنصة ويطلب التواصل عبر رقم خارجي مباشرة.',
            ],
        ];

        $created  = 0;
        $usedPost = [];

        foreach ($reports as $data) {
            $post = Post::where('title', $data['post_title'])->inRandomOrder()->first()
                ?? Post::whereNotIn('id', $usedPost)->inRandomOrder()->first();
            if (!$post) continue;
            $usedPost[] = $post->id;

            $reporter = User::where('id', '!=', $post->user_id)->inRandomOrder()->first();
            if (!$reporter) continue;

            Report::firstOrCreate([
                'post_id' => $post->id,
                'user_id' => $reporter->id,
            ], [
                'reason' => $data['reason'],
            ]);
            $created++;
        }

        $this->command->info('Reports seeded: ' . $created . ' records.');
    }
}
