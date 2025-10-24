<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Record;
use App\Models\Patient;
use App\Models\Diagnostic;
use Carbon\Carbon;

class RecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            [
                'rut' => '12345678-5',
                'tratamientos' => 'Antibiótico oral por 7 días. Reposo y líquidos.',
                'antecedentes_salud' => 'Asma en la infancia.',
                'fecha' => Carbon::parse('2025-09-02'),
            ],
            [
                'rut' => '87654321-K',
                'tratamientos' => 'Inhibidores de la bomba de protones por 4 semanas.',
                'antecedentes_salud' => 'Alergia a penicilina.',
                'fecha' => Carbon::parse('2025-09-11'),
            ],
            [
                'rut' => '20567890-1',
                'tratamientos' => 'Seguimiento sin tratamiento específico.',
                'antecedentes_salud' => 'Sin antecedentes relevantes.',
                'fecha' => Carbon::parse('2025-09-16'),
            ],
        ];

        foreach ($records as $r) {
            $rutClean = preg_replace('/[^0-9kK]/', '', $r['rut']);
            $patient = Patient::where('rut', $rutClean)->first();

            $diagnostic = null;
            if ($patient) {
                // Try to find an existing diagnostic for that patient on the same date.
                // NOTE: Do NOT auto-create a Diagnostic here — leave it null if none exists.
                $diagnostic = Diagnostic::where('patient_id', $patient->id)
                    ->where('date', $r['fecha']->format('Y-m-d'))
                    ->first();
            }

            Record::updateOrCreate(
                [
                    'patient_id' => $patient ? $patient->id : null,
                    'fecha' => $r['fecha'],
                ],
                [
                    'patient_id' => $patient ? $patient->id : null,
                    'diagnostic_id' => $diagnostic ? $diagnostic->id : null,
                    'tratamientos' => $r['tratamientos'],
                    'antecedentes_salud' => $r['antecedentes_salud'],
                    'fecha' => $r['fecha'],
                ]
            );
        }
    }
}
