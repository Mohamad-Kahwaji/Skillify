<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportpost($id){
        $reports = Report::where('post_id', $id)
        ->get();
        return view('',compact('reports'));
    }
}
