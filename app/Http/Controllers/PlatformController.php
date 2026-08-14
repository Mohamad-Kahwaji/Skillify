<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlatformController extends Controller
{
    public function about()
    {
        return Inertia::render('About', ['platform' => PlatformSetting::current()]);
    }

    public function edit()
    {
        return Inertia::render('SuperAdmin/PlatformSettings', ['settings' => PlatformSetting::current()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'contact_email'   => ['nullable', 'email', 'max:190'],
            'contact_phone'   => ['nullable', 'string', 'max:30'],
            'about_title'     => ['required', 'string', 'max:190'],
            'about_body'      => ['required', 'string', 'max:5000'],
        ]);

        PlatformSetting::current()->update($data);

        return back()->with('success', 'تم تحديث معلومات المنصة ووسائل التواصل.');
    }
}
