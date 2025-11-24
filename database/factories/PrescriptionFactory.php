<?php

namespace Database\Factories;

use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescriptionFactory extends Factory
{
    protected $model = Prescription::class;

    public function definition(): array
    {
        // Attempt to reference existing records; fallback to null/first
        $appointment = Appointment::inRandomOrder()->first();
        $patient = $appointment?->patient ?: Patient::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();

        return [
            'appointment_id' => $appointment?->id,
            'patient_id' => $patient?->id ?? Patient::factory(),
            'user_id' => $user?->id ?? User::factory(),
            'title' => $this->faker->randomElement(['Tratamiento antibiótico','Control de dolor','Seguimiento post-operatorio','Receta general']) . ' ' . $this->faker->word(),
            'content' => implode("\n", $this->faker->randomElements([
                'Amoxicilina 500mg cada 8h por 7 días',
                'Ibuprofeno 400mg cada 8h si dolor',
                'Omeprazol 20mg en ayunas por 14 días',
                'Paracetamol 500mg cada 6h si fiebre',
                'Suero oral 1 vaso cada 2h',
            ], $this->faker->numberBetween(2,4))),
            'indications' => $this->faker->optional()->sentence(10),
        ];
    }
}
