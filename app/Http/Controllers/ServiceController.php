<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Service;
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
        $query = Service::with(['category', 'subcategory', 'city', 'user'])
            ->where('user_id', '!=', $myId)
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
        $cities     = City::orderBy('name_en')->get(['id', 'name_en']);
        $categories = Category::orderBy('name_en')->get(['id', 'name_en']);

        // Attach identity verification status to each service's owner
        $ownerIds = $services->pluck('user_id')->filter()->unique();
        $identityMap = \App\Models\IdentityVerification::whereIn('user_id', $ownerIds)
            ->select('user_id', 'status')
            ->latest()
            ->get()
            ->keyBy('user_id');

        $services->getCollection()->transform(function ($s) use ($identityMap) {
            $s->identity_status = $identityMap[$s->user_id]?->status ?? null;
            return $s;
        });

        return Inertia::render('User/Services', [
            'services'   => $services,
            'cities'     => $cities,
            'categories' => $categories,
            'filters'    => $request->only(['q', 'city', 'category', 'price_type']),
        ]);
    }

    public function serviceDetails($id)
    {
        $service = Service::with(['user.businesses', 'business', 'category', 'subcategory', 'city'])->findOrFail($id);
        return Inertia::render('User/ServiceDetails', [
            'service' => $service,
            'authId'  => auth('users')->id(),
        ]);
    }

    public function createService(Request $request,$id)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description'     => 'nullable|string|max:1000',
        ]);

        $user     = auth('users')->user();
        $business = $user->businesses;

        if (!$business) {
            return back()->with('error', 'يجب أن يكون لديك حساب أعمال نشط لإضافة خدمة.');
        }

        Service::updateOrCreate([
            'user_id'         => $user->id,
            'business_id'     => $business->id,
            'name'            => $request->name,
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'description'     => $request->description,
            'status'          => 'pending',
        ]);

        return back()->with('success', 'تم إضافة الخدمة بنجاح، سيتم مراجعتها قريباً.');
    }


}
