<?php

namespace App\Http\Controllers;

use App\Models\ActiveTypebusiness;
use App\Models\Advertisement;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Conversation;
use App\Models\Service;
use App\Models\Subcategory;
use App\Services\GeminiIdentityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    private function user()
    {
        return Auth::guard('users')->user();
    }

    // ── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $userId = Auth::guard('users')->id();

        $postsCount         = $this->user()->posts()->count();
        $conversationsCount = Conversation::where('user_id_1', $userId)
            ->orWhere('user_id_2', $userId)->count();
        $servicesCount = Service::where('is_active', true)
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')
                    ->orWhere(function ($q2) use ($userId) {
                        $q2->where('user_id', '!=', $userId)
                            ->whereHas(
                                'user',
                                fn($u) =>
                                $u->whereHas('businesses', fn($b) => $b->where('status', 'active'))
                            );
                    });
            })->count();
        $recentPosts        = $this->user()->posts()->latest()->limit(4)->get();

        $recentServices = Service::where('is_active', true)
            ->where('status', 'approved')
            ->with(['category', 'subcategory', 'city'])
            ->latest()
            ->take(6)
            ->get();

        $topBusinesses = Business::with('user')
            ->where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        $recentAds = Advertisement::active()->latest()->take(3)->get();

        return Inertia::render('User/Dashboard', [
            'postsCount'         => $postsCount,
            'conversationsCount' => $conversationsCount,
            'servicesCount'      => $servicesCount,
            'recentServices'     => $recentServices,
            'topBusinesses'      => $topBusinesses,
            'recentAds'          => $recentAds,
        ]);
    }

    // ── Profile ───────────────────────────────────────────────────────────────

    public function profile(GeminiIdentityService $gemini)
    {
        $user          = $this->user()->load('businesses', 'services');
        $this->verifyExistingProfilePhoto($user, $gemini);
        $business      = $user->businesses;
        $gallery       = $business ? $business->gallery()->get() : collect();
        $userServices  = $user->services()->with(['category', 'subcategory', 'city'])->latest()->get();
        $activeTypes   = ActiveTypebusiness::orderBy('name')->get();
        $categories    = Category::orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();
        $cities        = City::orderBy('name')->get();
        $verification  = \App\Models\IdentityVerification::where('user_id', $user->id)->latest()->first();

        return Inertia::render('User/Profile', [
            'user'          => $user,
            'business'      => $business,
            'gallery'       => $gallery->values(),
            'userServices'  => $userServices,
            'activeTypes'   => $activeTypes,
            'categories'    => $categories,
            'subcategories' => $subcategories,
            'cities'        => $cities,
            'verification'  => $verification ? ['status' => $verification->status, 'rejection_reason' => $verification->rejection_reason] : null,
            'profilePhotoAiVerified' => (bool) $user->profile_photo_ai_verified,
        ]);
    }

    public function updateProfile(Request $request, GeminiIdentityService $gemini)
    {
        $request->validate([
            'first_name'    => 'required|string|max:60',
            'middle_name'   => 'nullable|string|max:60',
            'last_name'     => 'required|string|max:60',
            'phone'         => 'required|string|max:20',
            'city'          => 'required|string|max:60',
            'gender'        => 'required|in:male,female',
            'birthdate'     => 'required|date',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = $this->user();
        $data = $request->only('first_name', 'middle_name', 'last_name', 'phone', 'city', 'gender', 'birthdate');

        if ($request->hasFile('profile_photo')) {
            try {
                $portraitResult = $gemini->analyseProfilePhoto($request->file('profile_photo'));

                if (! ($portraitResult['is_human'] ?? false)) {
                    return back()->withErrors([
                        'profile_photo' => 'يجب رفع صورة شخصية واضحة لوجه إنسان. ' . ($portraitResult['reason'] ?? ''),
                    ]);
                }

                $data['profile_photo_ai_verified'] = true;
            } catch (\Throwable $exception) {
                Log::warning('فشل فحص الصورة الشخصية الجديدة عبر Gemini: ' . $exception->getMessage());
                $data['profile_photo_ai_verified'] = false;
            }

            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'تم تحديث المعلومات الشخصية بنجاح.');
    }

    private function verifyExistingProfilePhoto($user, GeminiIdentityService $gemini): void
    {
        if (! $user->profile_photo || $user->profile_photo_ai_verified !== null) {
            return;
        }

        try {
            $result = $gemini->analyseStoredProfilePhoto(Storage::disk('public')->path($user->profile_photo));
            $user->forceFill(['profile_photo_ai_verified' => (bool) ($result['is_human'] ?? false)])->save();
        } catch (\Throwable $exception) {
            Log::warning('فشل فحص الصورة الشخصية المحفوظة عبر Gemini: ' . $exception->getMessage());
            $user->forceFill(['profile_photo_ai_verified' => false])->save();
        }
    }

    public function verifyPassword()
    {
        return back()->with('success', 'تم التحقق من كلمة المرور.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $this->user()->update([
            'password'            => $request->new_password,
            'password_changed_at' => now(),
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }

    /**
     * A user permanently deleting their own account. Gated by the
     * confirm_admin_password:users middleware on the route.
     * Deletes any business account + services (and their stored images)
     * before removing the user row itself.
     */
    public function destroyAccount(Request $request)
    {
        $user = $this->user();

        foreach ($user->services as $service) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
        }

        if ($business = $user->businesses) {
            if ($business->image) {
                Storage::disk('public')->delete($business->image);
            }
        }

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        Auth::guard('users')->logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'تم حذف حسابك نهائياً.');
    }

    // ── Services ──────────────────────────────────────────────────────────────

    public function storeService(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'category'    => 'required|string|max:60',
            'subcategory' => 'required|string|max:60',
            'city'        => 'required|string|max:60',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'price'       => 'required|numeric|min:0',
            'price_type'  => 'required|in:usd,syp',
            'image'       => 'nullable|image|max:2048',
        ]);

        $user = $this->user();
        $business = $user->businesses;

        if (!$business || $business->status !== 'active') {
            return back()->with('error', 'يجب أن يكون لديك حساب أعمال نشط لإضافة خدمة.');
        }

        $data = $request->only('name', 'description', 'category', 'subcategory', 'city', 'latitude', 'longitude', 'price', 'price_type');
        $data['user_id']   = $user->id;
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return back()->with('success', 'تمت إضافة الخدمة بنجاح.');
    }

    public function updateService(Request $request, int $id)
    {
        $service = Service::where('id', $id)
            ->where('user_id', Auth::guard('users')->id())
            ->firstOrFail();

        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'city_id'        => 'required|exists:cities,id',
            'price'          => 'required|numeric|min:0',
            'price_type'     => 'required|in:usd,syp',
            'image'          => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'description', 'category_id', 'subcategory_id', 'city_id', 'price', 'price_type');

        if ($request->hasFile('image')) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return back()->with('success', 'تم تحديث الخدمة بنجاح.');
    }

    public function destroyService(int $id)
    {
        $service = Service::where('id', $id)
            ->where('user_id', Auth::guard('users')->id())
            ->firstOrFail();

        if ($service->image) Storage::disk('public')->delete($service->image);
        $service->delete();

        return back()->with('success', 'تم حذف الخدمة.');
    }

    // ── Services browse ───────────────────────────────────────────────────────

    public function servicesBrowse(Request $request)
    {
        $myId = Auth::guard('users')->id();

        $query = Service::where('is_active', true)
            ->where(function ($q) use ($myId) {
                $q->whereNull('user_id')
                    ->orWhere('user_id', '!=', $myId);
            });

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($s) => $s->where('name', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%"));
        }

        if ($request->filled('city'))       $query->where('city', $request->city);
        if ($request->filled('category'))   $query->where('category', $request->category);
        if ($request->filled('price_type')) $query->where('price_type', $request->price_type);

        $services   = $query->with('user')->latest()->paginate(12);

        $baseQuery  = Service::where('is_active', true)
            ->where(fn($q) => $q->whereNull('user_id')->orWhere('user_id', '!=', $myId));

        $cities     = (clone $baseQuery)->whereNotNull('city')->distinct()->pluck('city');
        $categories = (clone $baseQuery)->whereNotNull('category')->distinct()->pluck('category');

        return view('user.services', compact('services', 'cities', 'categories'));
    }

    // ── Conversations list ────────────────────────────────────────────────────

    public function conversations()
    {
        $userId = Auth::guard('users')->id();

        $conversations = Conversation::where('user_id_1', $userId)
            ->orWhere('user_id_2', $userId)
            ->with(['userOne.businesses', 'userTwo.businesses'])
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($c) {
                return array_merge($c->toArray(), [
                    'user_one' => $c->userOne ? array_merge($c->userOne->only(['id', 'first_name', 'last_name', 'profile_photo']), [
                        'businesses' => $c->userOne->businesses ? $c->userOne->businesses->only(['id', 'image']) : null,
                    ]) : null,
                    'user_two' => $c->userTwo ? array_merge($c->userTwo->only(['id', 'first_name', 'last_name', 'profile_photo']), [
                        'businesses' => $c->userTwo->businesses ? $c->userTwo->businesses->only(['id', 'image']) : null,
                    ]) : null,
                ]);
            });

        return Inertia::render('User/Conversations', [
            'conversations' => $conversations,
            'authId'        => $userId,
        ]);
    }

    // ── Chat ──────────────────────────────────────────────────────────────────

    public function startChat(Request $request)
    {
        $userId      = Auth::guard('users')->id();
        $otherUserId = (int) $request->input('business_user_id');

        $conversation = Conversation::where(function ($q) use ($userId, $otherUserId) {
            $q->where('user_id_1', $userId)->where('user_id_2', $otherUserId);
        })->orWhere(function ($q) use ($userId, $otherUserId) {
            $q->where('user_id_1', $otherUserId)->where('user_id_2', $userId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create(['user_id_1' => $userId, 'user_id_2' => $otherUserId]);
        }

        return redirect()->route('user.chat', $conversation->id);
    }
}
