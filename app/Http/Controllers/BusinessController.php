<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Business;
use App\Models\BusinessGallery;
use App\Models\SuperAdmin;
use App\Notifications\NewRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::withTrashed()->with('user')->latest()->get();
        return Inertia::render('Admin/Workers', ['businesses' => $businesses]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('users')->user();

        $request->validate([
            'name_job'               => 'required|string|max:120',
            'number'                 => 'required|string|max:40',
            'active_typebusiness_id' => 'required|exists:active_typebusinesses,id',
            'city'                   => 'nullable|string|max:100',
            'area'                   => 'nullable|string|max:100',
            'street'                 => 'nullable|string|max:150',
            'description'            => 'nullable|string|max:1000',
            'image'                  => 'required|image|max:2048',
            'gallery_images'         => 'nullable|array|max:20',
            'gallery_images.*'       => 'image|max:5120',
        ]);

        $data = $request->only('name_job', 'number', 'active_typebusiness_id', 'description', 'city', 'area', 'street');
        $data['city']     = $request->city ?: $user->city;
        $data['name']     = $user->first_name . ' ' . $user->last_name;
        $data['activity'] = $request->name_job;
        $data['user_id']  = $user->id;
        $data['status']   = 'pending';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('businesses', 'public');
        }

        $business = Business::create($data);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('galleries', 'public');
                BusinessGallery::create(['business_id' => $business->id, 'image' => $path, 'date' => now()]);
            }
        }

        // Upgrade user role so they can edit their pending business
        $user->syncRoles(['business_owner']);

        $senderName = $user->first_name . ' ' . $user->last_name;
        $notification = new NewRequestNotification($senderName, $business->id);

        Admin::all()->each(fn($admin) => $admin->notify($notification));
        SuperAdmin::all()->each(fn($sa) => $sa->notify($notification));

        return back()->with('success', 'تم إرسال طلب حساب الأعمال، سيتم مراجعته قريباً.');
    }

    public function edit(Request $request)
    {
        $business = Business::where('user_id', Auth::guard('users')->id())->firstOrFail();

        $request->validate([
            'name_job'    => 'required|string|max:120',
            'number'      => 'required|string|max:40',
            'city'        => 'nullable|string|max:100',
            'area'        => 'nullable|string|max:100',
            'street'      => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name_job', 'number', 'description', 'city', 'area', 'street');
        $data['activity'] = $request->input('name_job');

        if ($request->hasFile('image')) {
            if ($business->image) Storage::disk('public')->delete($business->image);
            $data['image'] = $request->file('image')->store('businesses', 'public');
        }

        $business->update($data);

        return back()->with('success', 'تم تحديث معلومات حساب الأعمال.');
    }

    public function resubmit()
    {
        $user     = Auth::guard('users')->user();
        $business = Business::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->firstOrFail();

        $business->update(['status' => 'pending']);

        $notification = new NewRequestNotification(
            $user->first_name . ' ' . $user->last_name,
            $business->id
        );
        Admin::all()->each(fn($admin) => $admin->notify($notification));
        SuperAdmin::all()->each(fn($sa) => $sa->notify($notification));

        return back()->with('success', 'تم إعادة إرسال الطلب، سيتم مراجعته قريباً.');
    }

    public function show(int $id)
    {
        $business = Business::withTrashed()->with('user')->findOrFail($id);
        return Inertia::render('Admin/WorkerDetails', ['business' => $business]);
    }

    public function destroy(int $id)
    {
        Business::findOrFail($id)->delete();
        return redirect()->route('admin.workers.index')->with('success', 'Business deleted.');
    }

}
