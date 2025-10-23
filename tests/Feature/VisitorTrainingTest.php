<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ClinicalCase;
use Spatie\Permission\Models\Role;
use App\Http\Middleware\EnsureVisitorAuthenticated;

class VisitorTrainingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_case_is_visible_on_training_ru()
    {
        // create admin role
        Role::create(['name' => 'admin']);

        // create admin user and assign role
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // create a clinical case created by admin that has no language/title_ru
        $case = ClinicalCase::create([
            'title_es' => 'Caso admin ES',
            'title' => 'Caso admin ES',
            'title_ru' => null,
            'language' => 'es',
            'created_by' => $admin->id,
        ]);

    // disable visitor auth middleware for this test
    $this->withoutMiddleware(EnsureVisitorAuthenticated::class);

    $response = $this->get(route('visitor.training.ru'));

        $response->assertStatus(200);
        $response->assertSee('Caso admin ES');
    }
}
