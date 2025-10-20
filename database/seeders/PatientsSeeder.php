<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Carbon\Carbon;

class PatientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patients = [
            [
                'rut' => '12345678-5',
                'name' => 'María',
                'apellido_paterno' => 'González',
                'apellido_materno' => 'Pérez',
                'birth_date' => Carbon::parse('1985-04-12'),
                'gender' => 'F',
                'adress' => 'Calle Falsa 123, Santiago'
            ],
            [
                'rut' => '87654321-K',
                'name' => 'Juan',
                'apellido_paterno' => 'Rodríguez',
                'apellido_materno' => 'Soto',
                'birth_date' => Carbon::parse('1990-11-03'),
                'gender' => 'M',
                'adress' => 'Av. Siempre Viva 742, Valparaíso'
            ],
            [
                'rut' => '20567890-1',
                'name' => 'Ana',
                'apellido_paterno' => 'Muñoz',
                'apellido_materno' => 'López',
                'birth_date' => Carbon::parse('2000-07-21'),
                'gender' => 'F',
                'adress' => 'Pasaje Luna 45, Concepción'
            ],
        ];

        foreach ($patients as $p) {
            $rutClean = preg_replace('/[^0-9kK]/', '', $p['rut']);
            Patient::updateOrCreate(
                ['rut' => $rutClean],
                [
                    'name' => $p['name'],
                    'apellido_paterno' => $p['apellido_paterno'],
                    'apellido_materno' => $p['apellido_materno'],
                    'birth_date' => $p['birth_date'],
                    'gender' => $p['gender'],
                    'adress' => $p['adress'],
                ]
            );
        }
    }
}
