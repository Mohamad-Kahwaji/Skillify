<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function getLocationAttribute()
{
    if ($this->business) {
        return [
            'latitude'  => $this->business->latitude,
            'longitude' => $this->business->longitude,
        ];
    }

    return [
        'latitude'  => $this->latitude,
        'longitude' => $this->longitude,
    ];
}
    public function index()
    {
        $businesses = Business::withTrashed()->latest()->get();
        return view('admin.workers.index', compact('businesses'));
    }

    public function store(Request $request)
    {
        $user = auth('users')->user();
        $request->validate([
            'name'        => 'required|string|max:255',
            'name_job'    => 'required|string|max:255',
            'number'      => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'activity'    => 'required|string',
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status'      => 'required|in:pending,active,rejected',
            'user_id'     => 'required|exists:users,id',
        ]);
        Business::create($request->all());
        
        return redirect()->route('admin.workers.index')->with('success', 'Business created.');
    }

    public function edit(int $id, Request $request)
    {
        $business = Business::findOrFail($id);
        $request->validate([
            'name'        => 'required|string|max:255',
            'name_job'    => 'required|string|max:255',
            'number'      => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'activity'    => 'required|string',
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'status'      => 'required|in:pending,active,rejected',
            'user_id'     => 'required|exists:users,id',
        ]);


        $business->update($request->all());
        return redirect()->route('admin.workers.index')->with('success', 'Business updated.');
    }

    public function destroy(int $id)
    {
        Business::findOrFail($id)->delete();
        return redirect()->route('admin.workers.index')->with('success', 'Business deleted.');
    }
}
