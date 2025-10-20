<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use App\Models\Diagnostic;
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
        return view('symptoms.create', compact('diagnostics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'diagnostic_id' => 'nullable|exists:diagnostics,id',
        ]);

        $symptom = Symptom::create($data);

        if (!empty($data['diagnostic_id'])) {
            $diag = Diagnostic::find($data['diagnostic_id']);
            $diag->symptoms()->attach($symptom->id);
        }

        return redirect()->route('symptoms.index')->with('success', 'Síntoma creado correctamente.');
    }

    public function edit(Symptom $symptom)
    {
        $diagnostics = Diagnostic::with('patient')->get();
        return view('symptoms.edit', compact('symptom', 'diagnostics'));
    }

    public function update(Request $request, Symptom $symptom)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $symptom->update($data);

        return redirect()->route('symptoms.index')->with('success', 'Síntoma actualizado.');
    }

    public function destroy(Symptom $symptom)
    {
        $symptom->delete();
        return redirect()->route('symptoms.index')->with('success', 'Síntoma eliminado.');
    }
}
