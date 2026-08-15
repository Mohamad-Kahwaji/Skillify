<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\GeminiIdentityService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::withTrashed()->with('user')->latest()->get();
        return Inertia::render('Admin/Workers', ['businesses' => $businesses]);
    }

    public function explore(Request $request)
    {
        $myId = Auth::guard('users')->id();
        $query = Business::with([
            'user.identityVerification',
            'user.services' => fn($services) => $services
                ->where('status', 'approved')
                ->where('is_active', true)
                ->latest()
                ->limit(1),
            'gallery',
        ])
            ->where('status', 'active')
            ->where('user_id', '!=', $myId);

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($businesses) use ($term) {
                $businesses->where('name', 'like', "%{$term}%")
                    ->orWhere('name_job', 'like', "%{$term}%")
                    ->orWhere('activity', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhereHas('user', fn($users) => $users
                        ->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"]));
            });
        }

        if ($request->filled('city')) {
            $city = $request->city;
            $query->where(fn($businesses) => $businesses
                ->where('city', $city)
                ->orWhereHas('user', fn($users) => $users->where('city', $city)));
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');
        if (is_numeric($lat) && is_numeric($lng)) {
            $latitude = (float) $lat;
            $longitude = (float) $lng;
            $items = $query->latest()->get()->map(function (Business $business) use ($latitude, $longitude) {
                $business->distance_km = $this->calculateDistanceKm($business, $latitude, $longitude);
                return $business;
            })->sortBy(fn(Business $business) => $business->distance_km ?? PHP_FLOAT_MAX)->values();
            $page = max((int) $request->input('page', 1), 1);
            $perPage = 12;
            $businesses = new LengthAwarePaginator(
                $items->forPage($page, $perPage),
                $items->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $businesses = $query->latest()->paginate(12)->withQueryString();
        }

        $cities = \App\Models\User::whereHas('businesses', fn($businesses) => $businesses->where('status', 'active'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return Inertia::render('User/Explore', [
            'businesses' => $businesses,
            'cities'     => $cities,
            'filters'    => $request->only(['q', 'city', 'lat', 'lng']),
        ]);
    }

    protected function calculateDistanceKm(Business $business, float $latitude, float $longitude): ?float
    {
        $businessLatitude = is_numeric($business->latitude) ? (float) $business->latitude : $business->user?->latitude;
        $businessLongitude = is_numeric($business->longitude) ? (float) $business->longitude : $business->user?->longitude;
        if (!is_numeric($businessLatitude) || !is_numeric($businessLongitude)) return null;

        $dLat = deg2rad($latitude - $businessLatitude);
        $dLng = deg2rad($longitude - $businessLongitude);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($businessLatitude)) * cos(deg2rad($latitude)) * sin($dLng / 2) ** 2;
        return round(6371 * (2 * atan2(sqrt($a), sqrt(1 - $a))), 2);
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
            'image'                  => 'nullable|image|max:2048',
        ]);

        $hasVerifiedProfilePhoto = $user->profile_photo && $user->profile_photo_ai_verified;
        if (! $hasVerifiedProfilePhoto && ! $request->hasFile('image')) {
            throw ValidationException::withMessages([
                'image' => 'ارفع صورة شخصية واضحة ليتم التحقق منها بالذكاء الاصطناعي.',
            ]);
        }

        $data = $request->only('name_job', 'number', 'active_typebusiness_id', 'description', 'latitude', 'longitude');
        $data['name']     = $user->first_name . ' ' . $user->last_name;
        $data['activity'] = $request->name_job;
        $data['user_id']  = $user->id;
        $data['status']   = 'pending';

        $imageReason = null;
        if ($request->hasFile('image')) {
            $imageReason = $this->verifyHumanImage($request->file('image'), $gemini);
            $data['image'] = $request->file('image')->store('businesses', 'public');
        } else {
            $extension = pathinfo($user->profile_photo, PATHINFO_EXTENSION) ?: 'jpg';
            $data['image'] = 'businesses/profile-' . $user->id . '-' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->copy($user->profile_photo, $data['image']);
        }

        Business::create($data);

        $message = 'تم إرسال طلب حساب الأعمال، سيتم مراجعته قريباً.';
        if ($imageReason) $message .= ' ✅ ' . $imageReason;

        return back()->with('success', $message);
    }

    public function edit(Request $request, GeminiIdentityService $gemini)
    {
        $user = Auth::guard('users')->user();
        $business = Business::where('user_id', $user->id)->firstOrFail();

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
