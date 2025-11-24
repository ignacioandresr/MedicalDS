<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('patient', 'user')->orderBy('date', 'desc')->get();
        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        // Últimas recetas creadas (limitadas) con paciente para mostrar contexto
        $prescriptions = Prescription::with('patient')
            ->orderByDesc('id')
            ->limit(25)
            ->get();
        return view('appointments.create', compact('patients','prescriptions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'time' => 'nullable',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        $appointment = Appointment::create($data);

        // Crear receta médica opcional si se proporcionan campos mínimos
        if ($request->filled('prescription_title') && $request->filled('prescription_content')) {
            Prescription::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'user_id' => auth()->id(),
                'title' => $request->input('prescription_title'),
                'content' => $request->input('prescription_content'),
                'indications' => $request->input('prescription_indications'),
            ]);
            return redirect()->route('appointments.show', $appointment)->with('success', 'Cita y receta creadas correctamente.');
        }

        return redirect()->route('appointments.show', $appointment)->with('success', 'Cita creada correctamente.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('patient', 'user');
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::all();
        return view('appointments.edit', compact('appointment', 'patients'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'time' => 'nullable',
            'notes' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $appointment->update($data);

        return redirect()->route('appointments.index')->with('success', 'Cita actualizada.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Cita eliminada.');
    }
}
