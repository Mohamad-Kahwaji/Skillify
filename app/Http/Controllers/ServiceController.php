<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\SuperAdmin;
use App\Notifications\NewServiceRequestNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['user', 'category', 'subcategory', 'city'])->latest()->get();
        return Inertia::render('Admin/Services', ['services' => $services]);
    }

    public function show(int $id)
    {
        $service = Service::with(['user', 'category', 'subcategory', 'city', 'business'])->findOrFail($id);
        return Inertia::render('Admin/ServiceDetails', ['service' => $service]);
    }

    public function toggle(int $id)
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);
        $status = $service->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Service {$status} successfully.");
    }

    public function destroy(int $id)
    {
        Service::findOrFail($id)->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }

    public function servicesusers(Request $request)
    {
        $myId  = auth('users')->id();
        $query = Service::with(['category', 'subcategory', 'city', 'user.identityVerification', 'user.businesses'])
            ->where(fn($q) => $q->where('user_id', '!=', $myId)->orWhereNull('user_id'))
            ->where('status', 'approved')
            ->where('is_active', true);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($s) => $s->where('name', 'like', "%$q%")->orWhere('description', 'like', "%$q%"));
        }
        if ($request->filled('city'))       $query->where('city_id', $request->city);
        if ($request->filled('category'))   $query->where('category_id', $request->category);
        if ($request->filled('price_type')) $query->where('price_type', $request->price_type);

        $services   = $query->latest()->paginate(12)->withQueryString();
        $cities     = City::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return Inertia::render('User/Services', [
            'services'   => $services,
            'cities'     => $cities,
            'categories' => $categories,
            'filters'    => $request->only(['q', 'city', 'category', 'price_type']),
            'authId'     => $myId,
        ]);
    }

    public function serviceDetails($id)
    {
        $service = Service::with(['user.businesses', 'user.identityVerification', 'business', 'category', 'subcategory', 'city'])->findOrFail($id);
        return Inertia::render('User/ServiceDetails', [
            'service' => $service,
            'authId'  => auth('users')->id(),
        ]);
    }

    public function createService(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'city_id'        => 'required|exists:cities,id',
            'price'          => 'required|numeric|min:0',
            'price_type'     => 'required|in:usd,syp',
            'description'    => 'nullable|string|max:1000',
            'image'          => 'nullable|image|max:2048',
        ]);

        $user     = auth('users')->user();
        $business = $user->businesses;

        if (!$business || $business->status !== 'active') {
            return back()->with('error', 'يجب أن يكون لديك حساب أعمال نشط لإضافة خدمة.');
        }

        $data = [
            'user_id'        => $user->id,
            'business_id'    => $business->id,
            'name'           => $request->name,
            'category_id'    => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'city_id'        => $request->city_id,
            'price'          => $request->price,
            'price_type'     => $request->price_type,
            'description'    => $request->description,
            'status'         => 'pending',
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);

        $notification = new NewServiceRequestNotification(
            $user->first_name . ' ' . $user->last_name,
            $service->name
        );
        Admin::all()->each(fn($admin) => $admin->notify($notification));
        SuperAdmin::all()->each(fn($sa)    => $sa->notify($notification));

        return back()->with('success', 'تم إضافة الخدمة بنجاح، سيتم مراجعتها قريباً.');
    }


}