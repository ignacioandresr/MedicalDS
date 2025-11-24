<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use App\Models\Enfermedad;
use App\Models\Alergia;
use App\Models\Cirugia;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $records = Record::with(['patient', 'diagnostic', 'enfermedades', 'alergias', 'cirugias'])->get();
        return response()->json($records);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        $enfermedades_ids = [];
        if ($request->has('enfermedades') && is_array($request->input('enfermedades'))) {
            $enfermedades_ids = $request->input('enfermedades');
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

        $alergias_ids = [];
        if ($request->has('alergias') && is_array($request->input('alergias'))) {
            $alergias_ids = $request->input('alergias');
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

        $cirugias_ids = [];
        if ($request->has('cirugias') && is_array($request->input('cirugias'))) {
            $cirugias_ids = $request->input('cirugias');
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

        $record->load(['patient', 'diagnostic', 'enfermedades', 'alergias', 'cirugias']);
        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $record = Record::with(['patient', 'diagnostic', 'enfermedades', 'alergias', 'cirugias'])->findOrFail($id);
        return response()->json($record);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $record = Record::findOrFail($id);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnostic_id' => 'nullable|exists:diagnostics,id',
            'antecedentes_salud' => 'nullable',
            'medicamentos' => 'nullable|string',
            'fecha' => 'required|date',
        ]);

        $record->update([
            'patient_id' => $request->patient_id,
            'diagnostic_id' => $request->diagnostic_id,
            'antecedentes_salud' => $request->antecedentes_salud,
            'medicamentos' => $request->medicamentos,
            'fecha' => $request->fecha,
        ]);

        $enfermedades_ids = [];
        if ($request->has('enfermedades') && is_array($request->input('enfermedades'))) {
            $enfermedades_ids = $request->input('enfermedades');
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

        $alergias_ids = [];
        if ($request->has('alergias') && is_array($request->input('alergias'))) {
            $alergias_ids = $request->input('alergias');
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

        $cirugias_ids = [];
        if ($request->has('cirugias') && is_array($request->input('cirugias'))) {
            $cirugias_ids = $request->input('cirugias');
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

        $record->load(['patient', 'diagnostic', 'enfermedades', 'alergias', 'cirugias']);
        return response()->json($record);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $record = Record::findOrFail($id);
        $record->delete();
        return response()->json(null, 204);
    }
}
