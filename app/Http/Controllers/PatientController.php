<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Diagnostic;
use App\Models\Record;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $patients = Patient::all();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rut' => 'required|unique:patients',
            'name' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'adress' => 'required',
        ]);
        $patient = Patient::create($request->all());
        try {
            $defaultDiagnostic = Diagnostic::create([
                'description' => 'Sin Diagnóstico',
                'date' => now()->toDateString(),
                'patient_id' => $patient->id,
                'user_id' => auth()->id(),
            ]);

            Record::create([
                'patient_id' => $patient->id,
                'diagnostic_id' => $defaultDiagnostic->id,
                'tratamientos' => 'Sin Tratamiento',
                'fecha' => now()->toDateString(),
            ]);
        } catch (\Exception $e) {
            $patient->delete();
            return redirect()->route('patients.index')->with('error', 'Error al crear historial médico: ' . $e->getMessage());
        }

        return redirect()->route('patients.index')->with('success', 'Paciente creado con éxito.');
    }

    public function show(Patient $patient)
    {
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'rut' => 'required|unique:patients,rut,' . $patient->id,
            'name' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'adress' => 'required',
        ]);

        $patient->update($request->all());
        return redirect()->route('patients.index')->with('success', 'Paciente actualizado con éxito.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Paciente eliminado con éxito.');
    }
}
