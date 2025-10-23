<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnostic;
use App\Models\Patient;
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
                'user_id' => 1,
            ],
            [
                'rut' => '87654321-K',
                'description' => 'Dolor abdominal persistente. Sospecha de gastritis.',
                'date' => Carbon::parse('2025-09-10'),
                'user_id' => 1,
            ],
            [
                'rut' => '20567890-1',
                'description' => 'Revisión anual sin hallazgos relevantes.',
                'date' => Carbon::parse('2025-09-15'),
                'user_id' => 2,
            ],
        ];

        foreach ($diagnostics as $d) {
            $rutClean = preg_replace('/[^0-9kK]/', '', $d['rut']);
            $patient = Patient::where('rut', $rutClean)->first();

            Diagnostic::updateOrCreate(
                [
                    'patient_id' => $patient ? $patient->id : null,
                    'description' => $d['description'],
                    'date' => $d['date'],
                ],
                [
                    'patient_id' => $patient ? $patient->id : null,
                    'description' => $d['description'],
                    'date' => $d['date'],
                    'user_id' => $d['user_id'] ?? null,
                ]
            );
        }
    }
}
