<?php
namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Patient;
use App\Models\Diagnostic;
use App\Models\Enfermedad;
use App\Models\Alergia;
use App\Models\Cirugia;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $records = Record::with(['patient', 'diagnostic', 'enfermedades', 'alergias', 'cirugias'])->get();
        return view('records.index', compact('records'));
    }

    public function create()
    {
    $patients = Patient::all();
    $diagnostics = Diagnostic::with('patient')->get();
    $enfermedades = Enfermedad::all();
    $alergias = Alergia::all();
    $cirugias = Cirugia::all();
    return view('records.create', compact('patients', 'diagnostics', 'enfermedades', 'alergias', 'cirugias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnostic_id' => 'required|exists:diagnostics,id',
            'antecedentes_salud' => 'required',
            'fecha' => 'required|date',
            'enfermedades' => 'nullable|array',
            'enfermedades.*' => 'exists:enfermedades,id',
            'alergias' => 'nullable|array',
            'alergias.*' => 'exists:alergias,id',
            'cirugias' => 'nullable|array',
            'cirugias.*' => 'exists:cirugias,id',
        ]);

        $record = Record::create([
            'patient_id' => $request->patient_id,
            'diagnostic_id' => $request->diagnostic_id,
            'antecedentes_salud' => $request->antecedentes_salud,
            'fecha' => $request->fecha,
        ]);

        // Sync relaciones many-to-many
        if ($request->has('enfermedades')) {
            $record->enfermedades()->sync($request->input('enfermedades'));
        }
        if ($request->has('alergias')) {
            $record->alergias()->sync($request->input('alergias'));
        }
        if ($request->has('cirugias')) {
            $record->cirugias()->sync($request->input('cirugias'));
        }

        return redirect()->route('records.index')->with('success', 'Historial médico creado correctamente.');
    }

    public function show(Record $record)
    {
        $record->load(['patient', 'diagnostic', 'enfermedades', 'alergias', 'cirugias']);
        return view('records.show', compact('record'));
    }

    public function edit(Record $record)
    {
    $patients = Patient::all();
    $diagnostics = Diagnostic::with('patient')->get();
    $enfermedades = Enfermedad::all();
    $alergias = Alergia::all();
    $cirugias = Cirugia::all();
    $record->load('enfermedades', 'alergias', 'cirugias');
    return view('records.edit', compact('record', 'patients', 'diagnostics', 'enfermedades', 'alergias', 'cirugias'));
    }

    public function update(Request $request, Record $record)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnostic_id' => 'required|exists:diagnostics,id',
            'antecedentes_salud' => 'required',
            'fecha' => 'required|date',
        ]);

        // No permitir cambiar el diagnostic_id desde el historial. Solo actualizar tratamientos/fecha/paciente.
        $record->update([
            'patient_id' => $request->patient_id,
            'antecedentes_salud' => $request->antecedentes_salud,
            'fecha' => $request->fecha,
        ]);

        if ($request->has('enfermedades')) {
            $record->enfermedades()->sync($request->input('enfermedades'));
        } else {
            $record->enfermedades()->sync([]);
        }
        if ($request->has('alergias')) {
            $record->alergias()->sync($request->input('alergias'));
        } else {
            $record->alergias()->sync([]);
        }
        if ($request->has('cirugias')) {
            $record->cirugias()->sync($request->input('cirugias'));
        } else {
            $record->cirugias()->sync([]);
        }
        return redirect()->route('records.index')->with('success', 'Historial médico actualizado correctamente.');
    }

    public function destroy(Record $record)
    {
        $record->delete();
        return redirect()->route('records.index')->with('success', 'Historial médico eliminado correctamente.');
    }
}
