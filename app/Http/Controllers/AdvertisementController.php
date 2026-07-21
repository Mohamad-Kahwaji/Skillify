<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'image'        => 'nullable|image|max:2048',
            'company_name' => 'nullable|string',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date',
            'status'       => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('ads', 'public');
        }

        Advertisement::create($validated);
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement created.');
    }

    public function update(Request $request, int $id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $validated = $request->validate([
            'title'        => 'required|string',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'company_name' => 'nullable|string',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date',
            'status'       => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($advertisement->image) Storage::disk('public')->delete($advertisement->image);
            $validated['image'] = $request->file('image')->store('ads', 'public');
        } else {
            unset($validated['image']);
        }

        $advertisement->update($validated);
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement updated.');
    }

    public function destroy(int $id)
    {
        $advertisement = Advertisement::findOrFail($id);
        if ($advertisement->image) Storage::disk('public')->delete($advertisement->image);
        $advertisement->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement deleted.');
    }

    public function userAds()
    {
        $advertisements = Advertisement::active()->latest()->get();

        return Inertia::render('User/Ads', ['advertisements' => $advertisements]);
    }
}
