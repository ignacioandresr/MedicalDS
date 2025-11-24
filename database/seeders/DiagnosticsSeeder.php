<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnostic;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class DiagnosticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $diagnostics = [
            [
                'rut' => '12345678-5',
                'description' => 'Infección respiratoria aguda. Tos y fiebre.',
                'date' => Carbon::parse('2025-09-01'),
                'user_index' => 0,
            ],
            [
                'rut' => '87654321-K',
                'description' => 'Dolor abdominal persistente. Sospecha de gastritis.',
                'date' => Carbon::parse('2025-09-10'),
                'user_index' => 0,
            ],
            [
                'rut' => '20567890-1',
                'description' => 'Revisión anual sin hallazgos relevantes.',
                'date' => Carbon::parse('2025-09-15'),
                'user_index' => 1,
            ],
        ];

        // Obtener listado de usuarios existentes; si faltan, crear los necesarios
        $existingUsers = User::orderBy('id')->get();
        if ($existingUsers->count() === 0) {
            $existingUsers->push(User::create([
                'name' => 'Admin Auto',
                'email' => 'admin.auto@example.com',
                'password' => bcrypt('password'),
            ]));
        }
        // Crear segundo usuario si se necesita un índice 1 y no existe
        $needsSecond = collect($diagnostics)->contains(fn($d) => ($d['user_index'] ?? 0) === 1);
        if ($needsSecond && $existingUsers->count() < 2) {
            $existingUsers->push(User::create([
                'name' => 'Medico Secundario',
                'email' => 'medico.secundario@example.com',
                'password' => bcrypt('password'),
            ]));
        }

        foreach ($diagnostics as $d) {
            $rutClean = preg_replace('/[^0-9kK]/', '', $d['rut']);
            $patient = null;
            if (!empty($rutClean)) {
                $patient = Patient::where('rut', $rutClean)->first();
            }

            // Si no encontramos paciente por RUT, usar el primer paciente existente como fallback
            $patientId = $patient ? $patient->id : null;
            if (!$patientId) {
                $firstPatient = Patient::first();
                if ($firstPatient) {
                    $patientId = $firstPatient->id;
                } else {
                    // No hay pacientes en la DB; saltar esta entrada para evitar violación de integridad
                    continue;
                }
            }

            $userIndex = $d['user_index'] ?? 0;
            $user = $existingUsers->get($userIndex) ?? $existingUsers->first();

            Diagnostic::updateOrCreate(
                [
                    'patient_id' => $patientId,
                    'description' => $d['description'],
                    'date' => $d['date'],
                ],
                [
                    'patient_id' => $patientId,
                    'description' => $d['description'],
                    'date' => $d['date'],
                    'user_id' => $user->id,
                ]
            );
        }
    }
}