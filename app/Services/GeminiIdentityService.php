<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GeminiIdentityService
{
    private string $apiKey;
    private array $models = ['gemini-2.5-flash-lite', 'gemini-2.5-flash', 'gemini-flash-latest'];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', '');
    }

    /**
     * Analyse an identity verification request.
     * Returns array: { match_score, extracted, verdict, notes }
     */
    public function analyse(\App\Models\IdentityVerification $verification): array
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('GEMINI_API_KEY غير مضبوط في ملف .env');
        }

        $parts = [];

        // Add images as base64 inline parts (front and back only — no selfie)
        foreach (['front_image', 'back_image'] as $field) {
            if ($verification->$field) {
                $path = Storage::disk('public')->path($verification->$field);
                if (file_exists($path)) {
                    $mime = mime_content_type($path);
                    $b64  = base64_encode(file_get_contents($path));
                    $label = match($field) {
                        'front_image'  => 'الوجه الأمامي للوثيقة',
                        'back_image'   => 'الوجه الخلفي للوثيقة',
                        'selfie_image' => 'صورة السيلفي مع الوثيقة',
                    };
                    $parts[] = ['text' => "=== $label ==="];
                    $parts[] = ['inline_data' => ['mime_type' => $mime, 'data' => $b64]];
                }
            }
        }

        $idTypeAr = $verification->id_type === 'passport' ? 'جواز سفر' : 'هوية وطنية';

        $parts[] = ['text' => <<<PROMPT
أنت محلل وثائق هوية محترف. راجع صور الوثيقة أعلاه وأجب بـ JSON فقط بدون أي نص إضافي.

بيانات المستخدم المُدخلة على المنصة:
- الاسم الكامل: {$verification->full_name}
- رقم الهوية: {$verification->id_number}
- نوع الوثيقة: {$idTypeAr}

المطلوب:
1. استخرج من الوثيقة: الاسم الكامل، رقم الهوية، تاريخ الانتهاء (إن وجد)، الجنس (إن وجد)
2. قارن ما استخرجته مع ما أدخله المستخدم
3. قيّم أصالة الوثيقة (هل تبدو حقيقية وغير مزوّرة؟)

أجب بهذا JSON الدقيق:
{
  "extracted": {
    "full_name": "الاسم المستخرج من الوثيقة أو null",
    "id_number": "الرقم المستخرج أو null",
    "expiry_date": "تاريخ الانتهاء أو null",
    "gender": "ذكر أو أنثى أو null"
  },
  "name_match": true أو false,
  "id_match": true أو false,
  "document_authentic": true أو false,
  "match_score": رقم من 0 إلى 100 يمثل ثقتك الكلية بصحة الطلب,
  "verdict": "approved" أو "review" أو "rejected",
  "notes": "ملاحظات مختصرة بالعربية في 1-2 جملة"
}

قواعد:
- verdict = "approved" إذا match_score >= 80 والوثيقة أصيلة
- verdict = "review" إذا match_score بين 50 و 79
- verdict = "rejected" إذا match_score < 50 أو الوثيقة مزوّرة
PROMPT
        ];

        $payload = [
            'contents' => [['parts' => $parts]],
            'generationConfig' => [
                'temperature'      => 0.1,
                'responseMimeType' => 'application/json',
            ],
        ];

        $response = null;
        $lastError = '';
        foreach ($this->models as $model) {
            $response = Http::timeout(45)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}",
                $payload
            );
            // Retry on 503/429 (unavailable / quota) with next model
            if ($response->status() === 503 || $response->status() === 429) {
                $lastError = $response->body();
                continue;
            }
            break;
        }

        if (!$response || $response->failed()) {
            throw new \RuntimeException('فشل الاتصال بـ Gemini API: ' . ($lastError ?: $response?->body()));
        }

        $text = $response->json('candidates.0.content.parts.0.text') ?? '{}';

        // Clean markdown fences if present
        $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
        $text = preg_replace('/\s*```$/i', '', $text);

        $result = json_decode($text, true);

        if (!is_array($result)) {
            throw new \RuntimeException('استجابة غير صالحة من Gemini: ' . $text);
        }

        return $result;
    }
}