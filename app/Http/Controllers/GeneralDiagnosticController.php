<?php
namespace App\Http\Controllers;

use App\Models\GeneralDiagnostic;
use App\Models\Symptom;
use Illuminate\Http\Request;

class GeneralDiagnosticController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = GeneralDiagnostic::with('symptoms')->latest()->paginate(20);
        return view('general_diagnostics.index', compact('items'));
    }

    public function create()
    {
        $symptoms = Symptom::all();
        return view('general_diagnostics.create', compact('symptoms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'date' => 'nullable|date',
            'symptoms' => 'nullable|array',
        ]);

        $gd = GeneralDiagnostic::create([
            'description' => $data['description'],
            'date' => $data['date'] ?? now()->toDateString(),
            'user_id' => auth()->id(),
        ]);

        if (!empty($data['symptoms'])) {
            $gd->symptoms()->sync(array_filter($data['symptoms']));
        }

        return redirect()->route('general-diagnostics.index')->with('success', 'Diagnóstico general creado.');
    }

    public function show(GeneralDiagnostic $general_diagnostic)
    {
        $general_diagnostic->load('symptoms');
        return view('general_diagnostics.show', ['item' => $general_diagnostic]);
    }

    public function edit(GeneralDiagnostic $general_diagnostic)
    {
        $symptoms = Symptom::all();
        $attached = $general_diagnostic->symptoms()->pluck('symptoms.id')->toArray();
        return view('general_diagnostics.edit', compact('general_diagnostic', 'symptoms', 'attached'));
    }

    public function update(Request $request, GeneralDiagnostic $general_diagnostic)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'date' => 'nullable|date',
            'symptoms' => 'nullable|array',
        ]);

        $general_diagnostic->update([
            'description' => $data['description'],
            'date' => $data['date'] ?? $general_diagnostic->date,
            'user_id' => auth()->id(),
        ]);

        $general_diagnostic->symptoms()->sync($data['symptoms'] ?? []);

        return redirect()->route('general-diagnostics.index')->with('success', 'Diagnóstico general actualizado.');
    }

    public function destroy(GeneralDiagnostic $general_diagnostic)
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('admin')) {
            abort(403, 'Acción no autorizada.');
        }

        $general_diagnostic->delete();
        return redirect()->route('general-diagnostics.index')->with('success', 'Diagnóstico general eliminado.');
    }
}
