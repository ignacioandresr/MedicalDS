<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
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
        $patients = Patient::all();
        return view('appointments.create', compact('patients'));
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

        Appointment::create($data);

        return redirect()->route('appointments.index')->with('success', 'Cita creada correctamente.');
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
