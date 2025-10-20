<?php
namespace App\Http\Controllers;

use App\Models\Diagnostic;
use App\Models\Patient;
use App\Models\Symptom;
use Illuminate\Http\Request as HttpRequest;
use App\Models\Record;

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
        $symptoms = Symptom::all();
        return view('diagnostics.create', compact('patients', 'symptoms'));
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

        $diagnostic = Diagnostic::create([
            'description' => $request->description,
            'date' => $request->date,
            'patient_id' => $patient->id,
            'user_id' => auth()->id(),
        ]);

        // sincronizar sintomas si vienen
        if ($request->has('symptoms')) {
            $diagnostic->symptoms()->sync(array_filter($request->input('symptoms')));
        }

        // Actualizar el historial médico más reciente del paciente para apuntar a este diagnóstico
        try {
            $latestRecord = Record::where('patient_id', $patient->id)->latest('fecha')->first();
            if ($latestRecord) {
                $latestRecord->update(['diagnostic_id' => $diagnostic->id]);
            }
        } catch (\Exception $e) {
            // No detener el flujo si falla la actualización del historial, pero podríamos registrar el error
        }

        return redirect()->route('diagnostics.index')->with('success', 'Diagnostic created successfully.');
    }

    public function show(Diagnostic $diagnostic)
    {
        return view('diagnostics.show', compact('diagnostic'));
    }

    public function edit(Diagnostic $diagnostic)
    {
        $patients = Patient::all();
        $symptoms = Symptom::all();
        $attached = $diagnostic->symptoms()->pluck('symptoms.id')->toArray();
        return view('diagnostics.edit', compact('diagnostic', 'patients', 'symptoms', 'attached'));
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
        // sincronizar sintomas
        if ($request->has('symptoms')) {
            $diagnostic->symptoms()->sync(array_filter($request->input('symptoms')));
        } else {
            // si no se envían sintomas, dejar vacio
            $diagnostic->symptoms()->sync([]);
        }
        // Al actualizar el diagnóstico, también podemos propagar el cambio al historial más reciente
        try {
            $latestRecord = Record::where('patient_id', $patient->id)->latest('fecha')->first();
            if ($latestRecord && $latestRecord->diagnostic_id === $diagnostic->id) {
                $latestRecord->update(['diagnostic_id' => $diagnostic->id]);
            }
        } catch (\Exception $e) {
            // ignore
        }
        return redirect()->route('diagnostics.index')->with('success', 'Diagnostic updated successfully.');
    }

    public function destroy(Diagnostic $diagnostic)
    {
        $diagnostic->delete();
        return redirect()->route('diagnostics.index')->with('success', 'Diagnostic deleted successfully.');
    }
}
