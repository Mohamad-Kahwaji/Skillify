<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class AdminBlockedController extends Controller
{
    public function index()
    {
        $users = User::where('status', 'inactive')
            ->withCount(['posts', 'services', 'comments'])
            ->with([
                'businesses',
                'services' => fn ($q) => $q->with(['category:id,name', 'subcategory:id,name', 'city:id,name'])->latest(),
            ])
            ->latest()
            ->get();
        return Inertia::render('Admin/Blocked', ['users' => $users]);
    }
}
