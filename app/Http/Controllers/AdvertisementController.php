<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::latest()->get();
        $adminId        = Auth::guard('admins')->id();
        return Inertia::render('Admin/Ads', ['advertisements' => $advertisements, 'adminId' => $adminId]);
    }

    public function create()
    {
        return Inertia::render('Admin/Ads');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id'     => 'required|exists:admins,id',
            'title'        => 'required|string',
            'description'  => 'nullable|string',
            'image'        => 'nullable|string',
            'company_name' => 'nullable|string',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date',
            'status'       => 'nullable|string',
        ]);

        Advertisement::create($validated);
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement created.');
    }

    public function edit(int $id)
    {
        $advertisement = Advertisement::findOrFail($id);
        return view('admin.ads.edit', compact('advertisement'));
    }

    public function update(Request $request, int $id)
    {
        $advertisement = Advertisement::findOrFail($id);
        $advertisement->update($request->all());
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement updated.');
    }

    public function destroy(int $id)
    {
        Advertisement::findOrFail($id)->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement deleted.');
    }

    public function userAds()
    {
        $advertisements = Advertisement::where('status', 'approved')
            ->where(function ($q) {
                $today = now()->toDateString();
                $q->whereNull('start_date')
                  ->orWhere(function ($q2) use ($today) {
                      $q2->where('start_date', '<=', $today)
                         ->where(function ($q3) use ($today) {
                             $q3->whereNull('end_date')
                                ->orWhere('end_date', '>=', $today);
                         });
                  });
            })
            ->latest()
            ->get();

        return Inertia::render('User/Ads', ['advertisements' => $advertisements]);
    }
}
