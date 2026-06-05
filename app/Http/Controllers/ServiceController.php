<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function show(int $id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.show', compact('service'));
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

    public function servicesusers(){
        $services = Service::with('user','business','category','subCategory')
        ->where('user_id','!=',auth('users')->id())
        ->where('status','=','approved')
        ->get();
        return view('servicesusers',compact('services'));
        }

    public function serviceDetails($id){
        $service = Service::with('user.businesses','business','category','subCategory')->findOrFail($id);
        return view('servicedetails',compact('service'));
    }
}
