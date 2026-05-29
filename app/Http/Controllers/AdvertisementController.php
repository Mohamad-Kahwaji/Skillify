<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::all();
        return view('',compact('advertisements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'company_name' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'string|default:pending'
        ]);

        $advertisement = Advertisement::create($validated);
        return redirect()->route('');
    }


    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::findOrFail($id);
        if (!$advertisement) return route('', ['message' => 'Not found']);

        $advertisement->update($request->all());
        return route('', ['message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        $advertisement = Advertisement::findOrFail($id);
        if (!$advertisement) return route('', ['message' => 'Not found']);

        $advertisement->delete();
        return route('', ['message' => 'Deleted successfully']);
    }
    public function destroysoft($id)
    {
        $advertisement = Advertisement::findOrFail($id)->where('end_date','<',now());
        if (!$advertisement) return route('', ['message' => 'Not found']);

        $advertisement->delete();
        return route('', ['message' => 'Deleted successfully']);
    }
}
