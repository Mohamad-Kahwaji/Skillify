<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCityController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->get();
        return Inertia::render('Admin/Cities', ['cities' => $cities]);
    }

    public function create()
    {
        return Inertia::render('Admin/Cities');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'governorate' => 'required|string|max:100',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);
        City::create($data);
        return back()->with('success', 'City added successfully.');
    }

    public function edit(int $id)
    {
        $city = City::findOrFail($id);
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'governorate' => 'required|string|max:100',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);
        City::findOrFail($id)->update($data);
        return back()->with('success', 'City updated successfully.');
    }

    public function destroy(int $id)
    {
        City::findOrFail($id)->delete();
        return back()->with('success', 'City deleted.');
    }
}
