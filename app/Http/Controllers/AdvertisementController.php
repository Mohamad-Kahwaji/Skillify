<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::latest()->get();
        return view('admin.ads.index', compact('advertisements'));
    }

    public function create()
    {
        return view('admin.ads.create');
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
}
