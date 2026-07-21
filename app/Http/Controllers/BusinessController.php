<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\GeminiIdentityService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::withTrashed()->with('user')->latest()->get();
        return Inertia::render('Admin/Workers', ['businesses' => $businesses]);
    }

    public function show(int $id)
    {
        $business = Business::withTrashed()->with('user')->findOrFail($id);
        return Inertia::render('Admin/WorkerDetails', ['business' => $business]);
    }

    public function destroy(int $id)
    {
        $business = Business::withTrashed()->with('user')->findOrFail($id);

        $business->user?->deleteServicesWithFiles();

        if ($business->image) {
            Storage::disk('public')->delete($business->image);
        }
        $business->delete();

        return back()->with('success', 'تم حذف حساب الأعمال وكل خدماته.');
    }

    /**
     * A user deleting their own business account (keeps the user account itself).
     */
    public function destroySelf()
    {
        $user = Auth::guard('users')->user();
        $business = $user->businesses;

        if (!$business) {
            return back()->with('error', 'لا يوجد حساب أعمال لحذفه.');
        }

        $user->deleteServicesWithFiles();

        if ($business->image) {
            Storage::disk('public')->delete($business->image);
        }
        $business->delete();
        $user->syncBusinessRole();

        return redirect()->route('user.profile')->with('success', 'تم حذف حساب الأعمال وكل خدماته.');
    }

    public function store(Request $request, GeminiIdentityService $gemini)
    {
        $user = Auth::guard('users')->user();

        $request->validate([
            'name_job'               => 'required|string|max:120',
            'number'                 => 'required|string|max:40',
            'active_typebusiness_id' => 'required|exists:active_typebusinesses,id',
            'latitude'               => 'required|numeric|between:-90,90',
            'longitude'              => 'required|numeric|between:-180,180',
            'description'            => 'nullable|string|max:1000',
            'image'                  => 'required|image|max:2048',
        ]);

        $data = $request->only('name_job', 'number', 'active_typebusiness_id', 'description', 'latitude', 'longitude');
        $data['name']     = $user->first_name . ' ' . $user->last_name;
        $data['activity'] = $request->name_job;
        $data['user_id']  = $user->id;
        $data['status']   = 'pending';

        $imageReason = null;
        if ($request->hasFile('image')) {
            $imageReason = $this->verifyHumanImage($request->file('image'), $gemini);
            $data['image'] = $request->file('image')->store('businesses', 'public');
        }

        Business::create($data);

        $message = 'تم إرسال طلب حساب الأعمال، سيتم مراجعته قريباً.';
        if ($imageReason) $message .= ' ✅ ' . $imageReason;

        return back()->with('success', $message);
    }

    public function edit(Request $request, GeminiIdentityService $gemini)
    {
        $business = Business::where('user_id', Auth::guard('users')->id())->firstOrFail();

        $request->validate([
            'name_job'    => 'required|string|max:120',
            'number'      => 'required|string|max:40',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name_job', 'number', 'description');
        $data['activity'] = $request->name_job;

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $data['latitude']  = $request->latitude;
            $data['longitude'] = $request->longitude;
        }

        $imageReason = null;
        if ($request->hasFile('image')) {
            $imageReason = $this->verifyHumanImage($request->file('image'), $gemini);
            if ($business->image) Storage::disk('public')->delete($business->image);
            $data['image'] = $request->file('image')->store('businesses', 'public');
        }

        $business->update($data);

        $message = 'تم تحديث معلومات حساب الأعمال.';
        if ($imageReason) $message .= ' ✅ ' . $imageReason;

        return back()->with('success', $message);
    }

    /**
     * التحقق عبر Gemini أن الصورة المرفوعة صورة حقيقية لإنسان.
     * ترفض الرفع برسالة validation إذا لم تكن كذلك، وتُرجع سبب القبول عند النجاح.
     * تسمح بالمرور (fail-open) إذا تعطل الاتصال بالـ API.
     */
    private function verifyHumanImage(UploadedFile $file, GeminiIdentityService $gemini): ?string
    {
        try {
            $result = $gemini->analyseProfilePhoto($file);

            if (!($result['is_human'] ?? true)) {
                throw ValidationException::withMessages([
                    'image' => 'الصورة يجب أن تكون صورة شخصية حقيقية لك. ' . ($result['reason'] ?? ''),
                ]);
            }

            return $result['reason'] ?? null;
        } catch (\RuntimeException | ConnectionException $e) {
            // fail-open: عطل الـ API (بما فيه انقطاع الاتصال/انتهاء المهلة) لا يمنع المستخدم، لكن نسجّل المشكلة
            Log::warning('فشل فحص صورة حساب الأعمال عبر Gemini: ' . $e->getMessage());
            return null;
        }
    }
   
}
