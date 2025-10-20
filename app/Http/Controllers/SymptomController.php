<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use App\Models\Diagnostic;
use App\Models\Patient;
use Illuminate\Http\Request;

class SymptomController extends Controller
{
    public function index()
    {
        $symptoms = Symptom::all();
        return view('symptoms.index', compact('symptoms'));
    }

    public function create()
    {
        $diagnostics = Diagnostic::with('patient')->get();
        $patients = Patient::all();
        return view('symptoms.create', compact('diagnostics', 'patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'diagnostic_id' => 'nullable|exists:diagnostics,id',
        ]);

        $symptom = Symptom::create([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'patient_id' => $data['patient_id'],
        ]);

        if (!empty($data['diagnostic_id'])) {
            $diag = Diagnostic::find($data['diagnostic_id']);
            $diag->symptoms()->attach($symptom->id);
        }

        return redirect()->route('symptoms.index')->with('success', 'Síntoma creado correctamente.');
    }

    public function edit(Symptom $symptom)
    {
        $diagnostics = Diagnostic::with('patient')->get();
        $patients = Patient::all();
        // get diagnostics currently attached to this symptom (ids)
        $attached = $symptom->diagnostics()->pluck('diagnostics.id')->toArray();
        return view('symptoms.edit', compact('symptom', 'diagnostics', 'patients', 'attached'));
    }

    public function update(Request $request, Symptom $symptom)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $symptom->update([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'patient_id' => $data['patient_id'],
        ]);

        // opcional: sincronizar asociacion a diagnostico (si se envía diagnostic_id)
        $diagId = $request->input('diagnostic_id');
        if ($diagId) {
            // asegurarse que el diagnóstico existe
            $symptom->diagnostics()->sync([$diagId]);
        }

        return redirect()->route('symptoms.index')->with('success', 'Síntoma actualizado.');
    }

    public function destroy(Symptom $symptom)
    {
        $symptom->delete();
        return redirect()->route('symptoms.index')->with('success', 'Síntoma eliminado.');
    }
}
