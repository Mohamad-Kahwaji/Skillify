<?php

namespace App\Http\Controllers;

use App\Models\ActiveTypebusiness;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Conversation;
use App\Models\Service;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
                         ->whereHas('user', fn($u) =>
                             $u->whereHas('businesses', fn($b) => $b->where('status', 'active'))
                         );
                  });
            })->count();
        $recentPosts        = $this->user()->posts()->latest()->limit(4)->get();

        return view('user.dashboard', compact('postsCount', 'conversationsCount', 'servicesCount', 'recentPosts'));
    }

    // ── Profile ───────────────────────────────────────────────────────────────

    public function profile()
    {
        $user             = $this->user()->load('businesses', 'services');
        $business         = $user->businesses;
        $userServices     = $user->services()->latest()->get();
        $activeTypes      = ActiveTypebusiness::orderBy('name_ar')->get();
        $categories       = Category::orderBy('name_ar')->get();
        $subcategories    = Subcategory::orderBy('name_ar')->get();
        $cities           = City::orderBy('name_ar')->get();

        return view('user.profile', compact(
            'user', 'business', 'userServices',
            'activeTypes', 'categories', 'subcategories', 'cities'
        ));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name'  => 'required|string|max:60',
            'phone'      => 'required|string|max:20',
            'city'       => 'required|string|max:60',
            'gender'     => 'required|in:male,female',
            'birthdate'  => 'required|date',
        ]);

        $this->user()->update($request->only('first_name','last_name','phone','city','gender','birthdate'));

        return back()->with('success', 'تم تحديث المعلومات الشخصية بنجاح.');
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

        $data = $request->only('name','description','category','subcategory','city','latitude','longitude','price','price_type');
        $data['user_id']   = Auth::guard('users')->id();
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

        $data = $request->only('name','description','category','subcategory','city','latitude','longitude','price','price_type');

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

    // ── Explore ───────────────────────────────────────────────────────────────

    public function explore(Request $request)
    {
        $userId = Auth::guard('users')->id();
        $query  = Business::where('status', 'active');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($b) => $b->where('name', 'like', "%$q%")
                ->orWhere('name_job', 'like', "%$q%")
                ->orWhere('activity', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%"));
        }

        if ($request->filled('activity')) {
            $query->where('activity', $request->activity);
        }

        $businesses = $query->latest()->paginate(12);

        $businessUserIds = $businesses->pluck('user_id');
        $conversations   = Conversation::where(function ($q) use ($userId, $businessUserIds) {
            $q->where('user_id_1', $userId)->whereIn('user_id_2', $businessUserIds);
        })->orWhere(function ($q) use ($userId, $businessUserIds) {
            $q->where('user_id_2', $userId)->whereIn('user_id_1', $businessUserIds);
        })->get()->keyBy(function ($c) use ($userId) {
            return $c->user_id_1 == $userId ? $c->user_id_2 : $c->user_id_1;
        });

        $businesses->getCollection()->transform(function ($b) use ($conversations) {
            $b->conversationId = isset($conversations[$b->user_id]) ? $conversations[$b->user_id]->id : null;
            return $b;
        });

        $activities = Business::where('status', 'active')->whereNotNull('activity')->distinct()->pluck('activity');

        return view('user.explore', compact('businesses', 'activities'));
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
            ->with(['userOne', 'userTwo'])
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->get();

        return view('user.conversations', compact('conversations'));
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
