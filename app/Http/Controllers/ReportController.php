<?php

namespace App\Http\Controllers;

use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('user')->latest()->get();
        return view('admin.reports.index', compact('reports'));
    }

    public function reportpost(int $id)
    {
        $reports = Report::with('user')->where('post_id', $id)->get();
        return view('admin.reports.post', compact('reports'));
    }
}
