<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralDiagnostic;
use Carbon\Carbon;

class GeneralDiagnosticsSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['description' => 'Hipertensión arterial esencial (HTA). Control y manejo farmacológico.', 'date' => Carbon::parse('2025-08-01'), 'user_id' => 1],
            ['description' => 'Diabetes mellitus tipo 2. Hiperglucemias intermitentes, necesidad de control metabólico.', 'date' => Carbon::parse('2025-07-20'), 'user_id' => 1],
            ['description' => 'Infección del tracto urinario (ITU) baja. Disuria y frecuencia.', 'date' => Carbon::parse('2025-06-30'), 'user_id' => 1],
            ['description' => 'Asma bronquial. Episodios de sibilancias y disnea de esfuerzo.', 'date' => Carbon::parse('2025-05-15'), 'user_id' => 1],
            ['description' => 'Bronquitis aguda probable. Tos productiva y malestar general.', 'date' => Carbon::parse('2025-10-02'), 'user_id' => 1],
            ['description' => 'Gripe (influenza) sospechada. Fiebre, mialgias y cefalea.', 'date' => Carbon::parse('2025-04-10'), 'user_id' => 1],
            ['description' => 'COVID-19 (infección por SARS-CoV-2) — síntomas respiratorios y anosmia.', 'date' => Carbon::parse('2025-03-18'), 'user_id' => 1],
            ['description' => 'Otitis media aguda. Dolor de oído y fiebre en pediatría.', 'date' => Carbon::parse('2025-02-22'), 'user_id' => 1],
            ['description' => 'Sinusitis aguda. Congestión nasal, dolor facial y cefalea.', 'date' => Carbon::parse('2025-01-30'), 'user_id' => 1],
            ['description' => 'Gastritis aguda/eritema gástrico. Dolor epigástrico y náuseas.', 'date' => Carbon::parse('2025-01-05'), 'user_id' => 1],
            ['description' => 'Alergia estacional (rinitis alérgica). Estornudos y prurito nasal.', 'date' => Carbon::parse('2024-11-10'), 'user_id' => 1],
            ['description' => 'Depresión mayor leve a moderada. Tristeza, anhedonia y trastornos del sueño.', 'date' => Carbon::parse('2024-12-01'), 'user_id' => 1],
            ['description' => 'Trastorno de ansiedad generalizada. Preocupación excesiva y síntomas somáticos.', 'date' => Carbon::parse('2024-12-15'), 'user_id' => 1],
            ['description' => 'Osteoartritis (artrosis) de rodilla. Dolor mecánico y limitación funcional.', 'date' => Carbon::parse('2024-09-05'), 'user_id' => 1],
            ['description' => 'Fractura de miembro superior (antebrazo) — manejo ortopédico.', 'date' => Carbon::parse('2024-08-20'), 'user_id' => 1],
            ['description' => 'Celulitis de piel y tejido blando. Eritema, calor y dolor local.', 'date' => Carbon::parse('2024-07-12'), 'user_id' => 1],
            ['description' => 'Infarto agudo de miocardio (antecedente/seguimiento).', 'date' => Carbon::parse('2024-06-03'), 'user_id' => 1],
            ['description' => 'Accidente cerebrovascular isquémico (ACV) — seguimiento neurológico.', 'date' => Carbon::parse('2024-05-01'), 'user_id' => 1],
            ['description' => 'Insuficiencia cardíaca crónica — control de insuficiencia y diuréticos.', 'date' => Carbon::parse('2024-04-18'), 'user_id' => 1],
            ['description' => 'Hipotiroidismo. Fatiga, ganancia de peso y constipación.', 'date' => Carbon::parse('2024-03-10'), 'user_id' => 1],
            ['description' => 'Dermatitis atópica / eccema. Prurito crónico y lesiones eczematosas.', 'date' => Carbon::parse('2024-02-05'), 'user_id' => 1],
            ['description' => 'Infección por Helicobacter pylori sospechada (dispepsia recurrente).', 'date' => Carbon::parse('2024-01-12'), 'user_id' => 1],
        ];

        foreach ($items as $it) {
            GeneralDiagnostic::updateOrCreate([
                'description' => $it['description'],
                'date' => $it['date'],
            ], [
                'description' => $it['description'],
                'date' => $it['date'],
                'user_id' => $it['user_id'] ?? 1,
            ]);
        }
    }
}
