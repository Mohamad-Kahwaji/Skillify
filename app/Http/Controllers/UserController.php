<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function getDistance($userid)
{
    $currentUser = auth('users')->user();
    $otherUser   = User::findOrFail($userid);

    $distance = $currentUser->distanceTo($otherUser);

    return response()->json([
        'distance' => $distance . ' km',
    ]);
}
    public function store(Request $request)
    {
        $user = auth('users')->user();
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_namae'=>'required|string',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'city' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');

    }
    public function allusers()
    {
        $users = User::withCount(['posts', 'services', 'comments'])
            ->with('businesses:id,user_id,status,name')
            ->latest()
            ->get();
        return Inertia::render('Admin/Users', ['users' => $users]);
    }

    public function active(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', 'User activated.');
    }

    public function inactive(User $user)
    {
        $user->update(['status' => 'inactive']);
        return back()->with('success', 'User deactivated.');
    }
    public function services_users(){
        $users = User::whereNotIn('id', function($query) {
            $query->select('user_id')->from('services');
        })->latest()->get();
        return Inertia::render('Admin/NoServicesUsers', ['users' => $users]);
    }

    public function myservices(){
        $services = Service::where('user_id', auth('users')->id())
            ->with(['category','subcategory','city'])
            ->latest()
            ->get();
        return Inertia::render('User/MyServices', [
            'services'      => $services,
            'categories'    => Category::orderBy('name')->get(['id','name']),
            'subcategories' => Subcategory::orderBy('name')->get(['id','name','category_id']),
            'cities'        => City::orderBy('name')->get(['id','name']),
        ]);
    }

    public function status_myservice(){
        return redirect()->route('user.my-services.list', ['filter' => 'pending']);
    }

    public function publicProfile(int $id)
    {
        $authId  = auth('users')->id();
        $profile = User::with([
            'businesses.gallery',
            'identityVerification',
            'services' => fn($q) => $q->where('is_active', true)->where('status', 'approved')->with(['category', 'city'])->latest()->limit(6),
            'posts'    => fn($q) => $q->latest()->limit(6),
        ])->findOrFail($id);

        return Inertia::render('User/PublicProfile', [
            'profile'        => $profile,
            'authId'         => $authId,
            'isSelf'         => $authId === $id,
            'verifyStatus'   => $profile->identityVerification?->status,
        ]);
    }

    // ملف المستخدم للمشاهدة من طرف الأدمن / السوبر أدمن (بدون أي إجراءات: مراسلة/تعديل)
    private function loadProfileForStaff(int $id): User
    {
        return User::with([
            'businesses.gallery',
            'identityVerification',
            'services' => fn($q) => $q->with(['category', 'city'])->latest(),
            'posts'    => fn($q) => $q->latest()->limit(10),
        ])->findOrFail($id);
    }

    public function adminProfile(int $user)
    {
        $profile = $this->loadProfileForStaff($user);

        return Inertia::render('Admin/UserProfile', [
            'profile'      => $profile,
            'verifyStatus' => $profile->identityVerification?->status,
        ]);
    }

    public function superAdminProfile(int $user)
    {
        $profile = $this->loadProfileForStaff($user);

        return Inertia::render('SuperAdmin/UserProfile', [
            'profile'      => $profile,
            'verifyStatus' => $profile->identityVerification?->status,
        ]);
    }

}
