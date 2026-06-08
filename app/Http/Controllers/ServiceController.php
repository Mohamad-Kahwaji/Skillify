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
        return view('user.servicesusers',compact('services'));
        }

    public function serviceDetails($id){
        $service = Service::with('user.businesses','business','category','subCategory')->findOrFail($id);
        return view('user.servicedetails',compact('service'));
    }

    public function createService(Request $request,$id)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description'     => 'nullable|string|max:1000',
        ]);

        $user     = auth('users')->user();
        $business = $user->businesses;

        if (!$business) {
            return back()->with('error', 'يجب أن يكون لديك حساب أعمال نشط لإضافة خدمة.');
        }

        Service::updateOrCreate([
            'user_id'         => $user->id,
            'business_id'     => $business->id,
            'name'            => $request->name,
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'description'     => $request->description,
            'status'          => 'pending',
        ]);

        return back()->with('success', 'تم إضافة الخدمة بنجاح، سيتم مراجعتها قريباً.');
    }


}
