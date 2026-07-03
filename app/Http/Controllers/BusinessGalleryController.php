<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessGalleryController extends Controller
{
    private function business(): Business
    {
        return Business::where('user_id', Auth::guard('users')->id())->firstOrFail();
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'   => 'required|array|max:20',
            'images.*' => 'image|max:5120',
        ]);

        $business = $this->business();

        foreach ($request->file('images') as $file) {
            $path = $file->store('galleries', 'public');
            BusinessGallery::create([
                'business_id' => $business->id,
                'image'       => $path,
                'date'        => now(),
            ]);
        }

        return back()->with('success', 'تم رفع الصور بنجاح.');
    }

    public function destroy(BusinessGallery $gallery)
    {
        $business = $this->business();
        abort_if($gallery->business_id !== $business->id, 403);

        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();

        return back()->with('success', 'تم حذف الصورة.');
    }
}

