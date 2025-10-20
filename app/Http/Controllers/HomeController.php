<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symptom;
use App\Models\Diagnostic;
use App\Models\Record;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $latestSymptoms = Symptom::orderBy('created_at', 'desc')->take(3)->get();
        $latestDiagnostics = Diagnostic::orderBy('created_at', 'desc')->take(3)->get();
        $latestRecords = Record::orderBy('created_at', 'desc')->take(3)->get();

        return view('home', compact('latestSymptoms', 'latestDiagnostics', 'latestRecords'));
    }
}
