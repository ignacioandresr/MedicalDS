<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Prescription;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrescriptionRetrievalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_prescription_with_related_data()
    {
        $user = User::factory()->create();
        $patient = Patient::create([
            'rut' => '11111111-1',
            'name' => 'Paciente',
            'apellido_paterno' => 'Prueba',
            'apellido_materno' => 'Uno',
            'birth_date' => '1990-01-01',
            'gender' => 'M',
            'adress' => 'Calle Falsa 123',
        ]);
        $prescription = Prescription::factory()->create([
            'user_id' => $user->id,
            'patient_id' => $patient->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/prescriptions/' . $prescription->id);

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $prescription->id,
                'title' => $prescription->title,
            ])
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'indications',
                'patient' => ['id','rut','name','apellido_paterno','apellido_materno'],
                'user' => ['id','name','email'],
                'created_at',
                'updated_at',
            ]);
    }
}
