<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\SuperAdmin;
use App\Notifications\NewServiceRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $query = Service::with(['category', 'subcategory', 'city', 'business', 'user.identityVerification', 'user.businesses'])
            ->where(fn($q) => $q->where('user_id', '!=', $myId)->orWhereNull('user_id'))
            ->where('status', 'approved')
            ->where('is_active', true);

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($s) use ($q) {
                $s->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('subcategory', fn($c) => $c->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('city', fn($c) => $c->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('user', fn($u) => $u->where('first_name', 'like', "%{$q}%")
                        ->orWhere('last_name', 'like', "%{$q}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"]));
            });
        }
        if ($request->filled('city'))       $query->where('city_id', $request->city);
        if ($request->filled('category'))   $query->where('category_id', $request->category);
        if ($request->filled('price_type')) $query->where('price_type', $request->price_type);
        if ($request->boolean('verified_only')) {
            $query->whereHas('user.identityVerification', fn($verification) => $verification->where('status', 'approved'));
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = $request->input('radius');
        $sort = $request->input('sort', 'nearest');

        if (is_numeric($lat) && is_numeric($lng)) {
            $latitude = (float) $lat;
            $longitude = (float) $lng;
            $items = $query->latest()->get()->map(function (Service $service) use ($latitude, $longitude) {
                $distance = $this->calculateDistanceKm($service, $latitude, $longitude);
                $service->distance_km = $distance;
                return $service;
            });

            if (is_numeric($radius) && (float) $radius >= 0) {
                $items = $items->filter(
                    fn(Service $service) =>
                    $service->distance_km === null || $service->distance_km <= (float) $radius
                );
            }

            if ($sort === 'price_low') {
                $items = $items->sortBy(fn(Service $service) => (float) $service->price)->values();
            } elseif ($sort === 'price_high') {
                $items = $items->sortByDesc(fn(Service $service) => (float) $service->price)->values();
            } elseif ($sort === 'newest') {
                $items = $items->sortByDesc(fn(Service $service) => $service->created_at?->timestamp ?? 0)->values();
            } else {
                $items = $items->sortBy(fn(Service $service) => $service->distance_km ?? PHP_FLOAT_MAX)->values();
            }

            $page = $request->input('page', 1);
            $perPage = 12;
            $paginated = new LengthAwarePaginator(
                $items->forPage($page, $perPage),
                $items->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            $services = $paginated;
        } else {
            $services = $query->when($sort === 'price_low', fn($q) => $q->orderBy('price', 'asc'))
                ->when($sort === 'price_high', fn($q) => $q->orderBy('price', 'desc'))
                ->when($sort === 'newest', fn($q) => $q->latest())
                ->when($sort === 'nearest', fn($q) => $q->latest())
                ->paginate(12)
                ->withQueryString();
        }

        $cities     = City::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return Inertia::render('User/Services', [
            'services'   => $services,
            'cities'     => $cities,
            'categories' => $categories,
            'filters'    => $request->only(['q', 'city', 'category', 'price_type', 'lat', 'lng', 'radius', 'sort', 'verified_only']),
            'authId'     => $myId,
        ]);
    }

    protected function calculateDistanceKm(Service $service, float $latitude, float $longitude): ?float
    {
        $location = null;

        if ($service->business && is_numeric($service->business->latitude) && is_numeric($service->business->longitude)) {
            $location = [$service->business->latitude, $service->business->longitude];
        } elseif ($service->user && $service->user->businesses && is_numeric($service->user->businesses->latitude) && is_numeric($service->user->businesses->longitude)) {
            $location = [$service->user->businesses->latitude, $service->user->businesses->longitude];
        } elseif ($service->user && is_numeric($service->user->latitude) && is_numeric($service->user->longitude)) {
            $location = [$service->user->latitude, $service->user->longitude];
        }

        if (! $location) {
            return null;
        }

        [$lat2, $lng2] = $location;
        $earthRadius = 6371;
        $dLat = deg2rad($latitude - $lat2);
        $dLng = deg2rad($longitude - $lng2);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat2)) * cos(deg2rad($latitude))
            * sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
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
