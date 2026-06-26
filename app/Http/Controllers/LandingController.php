<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index()
    {
        $ads = Advertisement::where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->take(6)
            ->get(['id', 'title', 'description', 'image', 'company_name']);

        $topProfessionals = Business::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get(['id', 'name', 'name_job', 'description', 'image', 'activity']);

        $categories = Category::withCount('subcategories')
            ->take(8)
            ->get(['id', 'name_ar', 'name_en']);

        $stats = [
            'professionals' => Business::where('status', 'approved')->count(),
            'users'         => User::count(),
            'categories'    => Category::count(),
            'services'      => Service::where('status', 'active')->count(),
        ];

        return Inertia::render('Landing', [
            'ads'              => $ads,
            'topProfessionals' => $topProfessionals,
            'categories'       => $categories,
            'stats'            => $stats,
        ]);
    }
}
