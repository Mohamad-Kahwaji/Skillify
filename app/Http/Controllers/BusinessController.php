<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::withTrashed()->latest()->get();
        return view('admin.workers.index', compact('businesses'));
    }

    public function store(Request $request)
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

        $data = $request->only('name_job', 'number', 'active_typebusiness_id', 'description', 'latitude', 'longitude');
        $data['name']     = $user->first_name . ' ' . $user->last_name;
        $data['activity'] = $request->name_job;
        $data['user_id']  = $user->id;
        $data['status']   = 'pending';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('businesses', 'public');
        }

        Business::create($data);

        return back()->with('success', 'تم إرسال طلب حساب الأعمال، سيتم مراجعته قريباً.');
    }

    public function edit(Request $request)
    {
        $business = Business::where('user_id', Auth::guard('users')->id())->firstOrFail();

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

        if ($request->hasFile('image')) {
            if ($business->image) Storage::disk('public')->delete($business->image);
            $data['image'] = $request->file('image')->store('businesses', 'public');
        }

        $business->update($data);

        return back()->with('success', 'تم تحديث معلومات حساب الأعمال.');
    }

    public function show(int $id)
    {
        $business = Business::withTrashed()->with('user')->findOrFail($id);
        return view('admin.workers.show', compact('business'));
    }

    public function destroy(int $id)
    {
        Business::findOrFail($id)->delete();
        return redirect()->route('admin.workers.index')->with('success', 'Business deleted.');
    }

}
