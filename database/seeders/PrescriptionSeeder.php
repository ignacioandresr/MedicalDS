<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        // Avoid duplicate massive seeding if already seeded
        if (Prescription::count() > 0) {
            return; // idempotency simple
        }

        $users = User::all();
        if ($users->isEmpty()) {
            // ensure at least one user exists via factory if missing
            $users->push(User::factory()->create());
        }
        $defaultUser = $users->first();

        // Create prescriptions for existing appointments
        $appointments = Appointment::with('patient')->get();
        foreach ($appointments as $appointment) {
            $count = rand(1, 3);
            Prescription::factory($count)->create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'user_id' => $defaultUser->id,
            ]);
        }

        // Additional standalone prescriptions for patients without appointments
        $patientsWithAppointment = $appointments->pluck('patient_id')->unique();
        $extraPatients = Patient::whereNotIn('id', $patientsWithAppointment)->get();
        foreach ($extraPatients as $patient) {
            Prescription::factory(rand(1,2))->create([
                'patient_id' => $patient->id,
                'appointment_id' => null,
                'user_id' => $defaultUser->id,
            ]);
        }
    }
}
