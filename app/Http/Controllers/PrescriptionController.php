<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function __construct()
    {
        // Solo usuarios autenticados con rol admin o user
        $this->middleware(['auth','role:admin|user']);
    }
    public function index(Request $request)
    {
        $query = Prescription::with('appointment.patient','patient','user');
        $appointmentId = $request->get('appointment_id');
        if ($appointmentId) {
            $query->where('appointment_id', $appointmentId);
        }
        $prescriptions = $query->orderByDesc('id')->get();
        $currentAppointment = $appointmentId ? Appointment::with('patient')->find($appointmentId) : null;
        return view('prescriptions.index', compact('prescriptions','currentAppointment'));
    }

    public function create(Request $request)
    {
        $appointmentId = $request->get('appointment_id');
        $appointment = $appointmentId ? Appointment::with('patient')->find($appointmentId) : null;
        $patients = Patient::orderBy('name')->get();
        return view('prescriptions.create', compact('appointment','patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'patient_id' => 'required_without:appointment_id|exists:patients,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'indications' => 'nullable|string',
        ]);

        // If appointment provided, derive patient
        if (!empty($data['appointment_id'])) {
            $appointment = Appointment::find($data['appointment_id']);
            if ($appointment) {
                $data['patient_id'] = $appointment->patient_id;
            }
        }
        $data['user_id'] = auth()->id();

        $prescription = Prescription::create($data);
        return redirect()->route('prescriptions.show', $prescription)->with('success','Receta creada.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load('appointment.patient','patient','user');
        return view('prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        $prescription->load('appointment.patient','patient');
        $patients = Patient::orderBy('name')->get();
        return view('prescriptions.edit', compact('prescription','patients'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'indications' => 'nullable|string',
        ]);
        $prescription->update($data);
        return redirect()->route('prescriptions.show', $prescription)->with('success','Receta actualizada.');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->route('prescriptions.index')->with('success','Receta eliminada.');
    }
}
