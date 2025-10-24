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
    // No se envían listas de enfermedades/alergias/cirugías al formulario (se han eliminado de la vista)
    return view('records.create', compact('patients', 'diagnostics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnostic_id' => 'nullable|exists:diagnostics,id',
            'antecedentes_salud' => 'nullable',
            'medicamentos' => 'nullable|string',
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
            'medicamentos' => $request->medicamentos,
            'fecha' => $request->fecha,
        ]);
        // Sync relaciones many-to-many, permitiendo nombres libres en campos *_text
        // Enfermedades
        $enfermedades_ids = [];
        if ($request->has('enfermedades') && is_array($request->input('enfermedades'))) {
            foreach ($request->input('enfermedades') as $id) {
                $enfermedades_ids[] = (int) $id;
            }
        }
        $names = [];
        if ($request->filled('enfermedades_text')) {
            $names = preg_split('/,\s*/', $request->input('enfermedades_text'));
        }
        foreach ($names as $name) {
            $name = trim($name);
            if ($name === '') continue;
            $model = Enfermedad::firstOrCreate(['name' => $name]);
            $enfermedades_ids[] = $model->id;
        }
        $record->enfermedades()->sync(array_values(array_unique($enfermedades_ids)));

        // Alergias
        $alergias_ids = [];
        if ($request->has('alergias') && is_array($request->input('alergias'))) {
            foreach ($request->input('alergias') as $id) {
                $alergias_ids[] = (int) $id;
            }
        }
        $names = [];
        if ($request->filled('alergias_text')) {
            $names = preg_split('/,\s*/', $request->input('alergias_text'));
        }
        foreach ($names as $name) {
            $name = trim($name);
            if ($name === '') continue;
            $model = Alergia::firstOrCreate(['name' => $name]);
            $alergias_ids[] = $model->id;
        }
        $record->alergias()->sync(array_values(array_unique($alergias_ids)));

        // Cirugías
        $cirugias_ids = [];
        if ($request->has('cirugias') && is_array($request->input('cirugias'))) {
            foreach ($request->input('cirugias') as $id) {
                $cirugias_ids[] = (int) $id;
            }
        }
        $names = [];
        if ($request->filled('cirugias_text')) {
            $names = preg_split('/,\s*/', $request->input('cirugias_text'));
        }
        foreach ($names as $name) {
            $name = trim($name);
            if ($name === '') continue;
            $model = Cirugia::firstOrCreate(['name' => $name]);
            $cirugias_ids[] = $model->id;
        }
        $record->cirugias()->sync(array_values(array_unique($cirugias_ids)));

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
    // Cargamos relaciones en caso de uso posterior, pero no enviamos listas de selección a la vista
    $record->load('enfermedades', 'alergias', 'cirugias');
    return view('records.edit', compact('record', 'patients', 'diagnostics'));
    }

    public function update(Request $request, Record $record)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnostic_id' => 'nullable|exists:diagnostics,id',
            // Permitir que observaciones/antecedentes queden vacíos
            'antecedentes_salud' => 'nullable',
            'medicamentos' => 'nullable|string',
            'fecha' => 'required|date',
        ]);

        // No permitir cambiar el diagnostic_id desde el historial. Solo actualizar tratamientos/fecha/paciente.
        $record->update([
            'patient_id' => $request->patient_id,
            'diagnostic_id' => $request->diagnostic_id,
            'antecedentes_salud' => $request->antecedentes_salud,
            'medicamentos' => $request->medicamentos,
            'fecha' => $request->fecha,
        ]);

        // Enfermedades: combine selected IDs and free-text names
        $enfermedades_ids = [];
        if ($request->has('enfermedades') && is_array($request->input('enfermedades'))) {
            foreach ($request->input('enfermedades') as $id) {
                $enfermedades_ids[] = (int) $id;
            }
        }
        if ($request->filled('enfermedades_text')) {
            $names = preg_split('/,\s*/', $request->input('enfermedades_text'));
            foreach ($names as $name) {
                $name = trim($name);
                if ($name === '') continue;
                $model = Enfermedad::firstOrCreate(['name' => $name]);
                $enfermedades_ids[] = $model->id;
            }
        }
        $record->enfermedades()->sync(array_values(array_unique($enfermedades_ids)));

        // Alergias
        $alergias_ids = [];
        if ($request->has('alergias') && is_array($request->input('alergias'))) {
            foreach ($request->input('alergias') as $id) {
                $alergias_ids[] = (int) $id;
            }
        }
        if ($request->filled('alergias_text')) {
            $names = preg_split('/,\s*/', $request->input('alergias_text'));
            foreach ($names as $name) {
                $name = trim($name);
                if ($name === '') continue;
                $model = Alergia::firstOrCreate(['name' => $name]);
                $alergias_ids[] = $model->id;
            }
        }
        $record->alergias()->sync(array_values(array_unique($alergias_ids)));

        // Cirugías
        $cirugias_ids = [];
        if ($request->has('cirugias') && is_array($request->input('cirugias'))) {
            foreach ($request->input('cirugias') as $id) {
                $cirugias_ids[] = (int) $id;
            }
        }
        if ($request->filled('cirugias_text')) {
            $names = preg_split('/,\s*/', $request->input('cirugias_text'));
            foreach ($names as $name) {
                $name = trim($name);
                if ($name === '') continue;
                $model = Cirugia::firstOrCreate(['name' => $name]);
                $cirugias_ids[] = $model->id;
            }
        }
        $record->cirugias()->sync(array_values(array_unique($cirugias_ids)));
        return redirect()->route('records.index')->with('success', 'Historial médico actualizado correctamente.');
    }

    public function destroy(Record $record)
    {
        $record->delete();
        return redirect()->route('records.index')->with('success', 'Historial médico eliminado correctamente.');
    }
}
