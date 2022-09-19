<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    public function test_if_auth_user_can_see_users_list()
    {
        $this->withExceptionHandling();

        User::factory(5)->create();

        Sanctum::actingAs(
            $user = User::factory()->create([
                    'name' => 'Pedro',
                    'email' => 'pedro@mail.com',
                    'password' => 12345
            ])
        );

        $response = $this->get('/api');        
        $response->assertStatus(200);
    } 

    public function test_if_auth_user_can_see_users_list_optional()
    {
        $this->withoutExceptionHandling();

        User::factory()->create();

        Sanctum::actingAs(
            $user = User::factory()->create()
        );
    
        $response = $this->get('/api');        
        $response->assertStatus(200);
    } 

}
