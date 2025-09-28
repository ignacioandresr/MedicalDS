<?php
namespace App\Http\Controllers;

use App\Models\Diagnostic;
use App\Models\Patient;
use Illuminate\Http\Request as HttpRequest;

class DiagnosticController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $diagnostics = Diagnostic::all();
        return view('diagnostics.index', compact('diagnostics'));
    }

    public function create()
    {
        $patients = Patient::all();
        return view('diagnostics.create', compact('patients'));
    }

    public function store(HttpRequest $request)
    {
        $rut = preg_replace('/[^0-9kK]/', '', $request->patient_rut);
        $request->merge(['patient_rut' => $rut]);
        $request->validate([
            'description' => 'required',
            'date' => 'required|date',
            'patient_rut' => 'required|exists:patients,rut',
        ]);

        $patient = Patient::where('rut', $rut)->first();

        Diagnostic::create([
            'description' => $request->description,
            'date' => $request->date,
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('diagnostics.index')->with('success', 'Diagnostic created successfully.');
    }

    public function show(Diagnostic $diagnostic)
    {
        return view('diagnostics.show', compact('diagnostic'));
    }

    public function edit(Diagnostic $diagnostic)
    {
        $patients = Patient::all();
        return view('diagnostics.edit', compact('diagnostic', 'patients'));
    }

    public function update(HttpRequest $request, Diagnostic $diagnostic)
    {
        $rut = preg_replace('/[^0-9kK]/', '', $request->patient_rut);
        $request->merge(['patient_rut' => $rut]);
        $request->validate([
            'description' => 'required',
            'date' => 'required|date',
            'patient_rut' => 'required|exists:patients,rut',
        ]);

        $patient = Patient::where('rut', $rut)->first();

        $diagnostic->update([
            'description' => $request->description,
            'date' => $request->date,
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('diagnostics.index')->with('success', 'Diagnostic updated successfully.');
    }

    public function destroy(Diagnostic $diagnostic)
    {
        $diagnostic->delete();
        return redirect()->route('diagnostics.index')->with('success', 'Diagnostic deleted successfully.');
    }
}
