<?php
namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Patient;
use App\Models\Diagnostic;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $records = Record::with(['patient', 'diagnostic'])->get();
        return view('records.index', compact('records'));
    }

    public function create()
    {
    $patients = Patient::all();
    $diagnostics = Diagnostic::with('patient')->get();
    return view('records.create', compact('patients', 'diagnostics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            // diagnostic_id no se valida/actualiza desde el Historial: se gestiona en Diagnósticos
            'tratamientos' => 'required',
            'fecha' => 'required|date',
        ]);

        Record::create([
            'patient_id' => $request->patient_id,
            'diagnostic_id' => $request->diagnostic_id,
            'tratamientos' => $request->tratamientos,
            'fecha' => $request->fecha,
        ]);

        return redirect()->route('records.index')->with('success', 'Historial médico creado correctamente.');
    }

    public function show(Record $record)
    {
        $record->load(['patient', 'diagnostic']);
        return view('records.show', compact('record'));
    }

    public function edit(Record $record)
    {
    $patients = Patient::all();
    $diagnostics = Diagnostic::with('patient')->get();
    return view('records.edit', compact('record', 'patients', 'diagnostics'));
    }

    public function update(Request $request, Record $record)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnostic_id' => 'required|exists:diagnostics,id',
            'tratamientos' => 'required',
            'fecha' => 'required|date',
        ]);

        // No permitir cambiar el diagnostic_id desde el historial. Solo actualizar tratamientos/fecha/paciente.
        $record->update([
            'patient_id' => $request->patient_id,
            'tratamientos' => $request->tratamientos,
            'fecha' => $request->fecha,
        ]);
        return redirect()->route('records.index')->with('success', 'Historial médico actualizado correctamente.');
    }

    public function destroy(Record $record)
    {
        $record->delete();
        return redirect()->route('records.index')->with('success', 'Historial médico eliminado correctamente.');
    }
}
