<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('user')->latest()->get();
        return Inertia::render('Admin/Reports', ['reports' => $reports]);
    }

    public function reportpost(int $id)
    {
        $reports = Report::with('user')->where('post_id', $id)->get();
        return Inertia::render('Admin/Reports', ['reports' => $reports]);
    }
}
