<?php

namespace App\Http\Controllers;

use App\Models\IdentityVerification;
use App\Services\GeminiIdentityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class IdentityVerificationController extends Controller
{
    // ── User: show form / current status ─────────────────────────────────────
    public function show()
    {
        $user         = Auth::guard('users')->user();
        $verification = IdentityVerification::where('user_id', $user->id)->latest()->first();
        return view('user.identity-verification', compact('user', 'verification'));
    }

    // ── User: submit request ──────────────────────────────────────────────────
    public function store(Request $request)
    {
        $user = Auth::guard('users')->user();

        // Only one pending/approved at a time
        $existing = IdentityVerification::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'لديك طلب توثيق نشط بالفعل.');
        }

        $data = $request->validate([
            'full_name'    => 'required|string|max:100',
            'id_number'    => 'required|string|max:50',
            'id_type'      => 'required|in:national_id,passport',
            'front_image'  => 'required|image|max:5120',
            'back_image'   => 'nullable|image|max:5120',
            'selfie_image' => 'nullable|image|max:5120',
        ]);

        $paths = [];
        foreach (['front_image', 'back_image', 'selfie_image'] as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('identity', 'public');
            }
        }

        IdentityVerification::create([
            'user_id'      => $user->id,
            'full_name'    => $data['full_name'],
            'id_number'    => $data['id_number'],
            'id_type'      => $data['id_type'],
            'front_image'  => $paths['front_image'],
            'back_image'   => $paths['back_image'] ?? null,
            'selfie_image' => $paths['selfie_image'] ?? null,
        ]);

        return back()->with('success', 'تم إرسال طلب التوثيق بنجاح. سنراجعه قريباً.');
    }

    // ── Admin: list all verifications ─────────────────────────────────────────
    public function adminIndex()
    {
        $verifications = IdentityVerification::with(['user', 'reviewer'])
            ->latest()
            ->get();

        return Inertia::render('Admin/IdentityVerifications', [
            'verifications' => $verifications,
        ]);
    }

    // ── Admin: approve ────────────────────────────────────────────────────────
    public function approve(IdentityVerification $verification)
    {
        $verification->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::guard('admins')->id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'تم قبول طلب التوثيق.');
    }

    // ── Admin: reject ─────────────────────────────────────────────────────────
    public function reject(Request $request, IdentityVerification $verification)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $verification->update([
            'status'           => 'rejected',
            'reviewed_by'      => Auth::guard('admins')->id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('success', 'تم رفض طلب التوثيق.');
    }

    // ── Admin: analyse all without extracted_data ────────────────────────────
    public function analyseAll(GeminiIdentityService $gemini)
    {
        set_time_limit(300);

        $verifications = IdentityVerification::where('status', 'pending')->get();

        $done = 0;
        $failed = 0;

        foreach ($verifications as $verification) {
            try {
                $result = $gemini->analyse($verification);
                $verification->update([
                    'match_score'    => $result['match_score'] ?? null,
                    'extracted_data' => $result,
                ]);
                $done++;
                usleep(600000);
            } catch (\RuntimeException $e) {
                $failed++;
            }
        }

        $msg = "تم تحليل {$done} طلب بالذكاء الاصطناعي.";
        if ($failed > 0) $msg .= " فشل تحليل {$failed} طلب.";

        return back()->with('success', $msg);
    }

    // ── Admin: reset to pending ───────────────────────────────────────────────
    public function resetToPending(IdentityVerification $verification)
    {
        $verification->update([
            'status'           => 'pending',
            'reviewed_by'      => null,
            'reviewed_at'      => null,
            'rejection_reason' => null,
        ]);
        return back()->with('success', 'تمت إعادة الطلب إلى قيد المراجعة.');
    }

    // ── Admin: AI analysis (Gemini) ───────────────────────────────────────────
    public function analyseWithAi(IdentityVerification $verification, GeminiIdentityService $gemini)
    {
        try {
            $result = $gemini->analyse($verification);

            $verification->update([
                'match_score'    => $result['match_score'] ?? null,
                'extracted_data' => $result,
            ]);

            return back()->with('success', sprintf(
                'تم تحليل الهوية بنجاح. نسبة التطابق: %d%% — التوصية: %s',
                $result['match_score'] ?? 0,
                match($result['verdict'] ?? '') {
                    'approved' => '✅ قبول',
                    'rejected' => '❌ رفض',
                    default    => '⚠️ مراجعة يدوية',
                }
            ));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
