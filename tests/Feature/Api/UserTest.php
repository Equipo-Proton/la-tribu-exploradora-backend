<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

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

        User::factory()->create();

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

    public function test_user_can_not_see_users_list()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
    
        $this->actingAs($user);

        $response = $this->get('/api');
        $response->assertStatus(401);
    }

    public function test_user_profile_can_be_updated_by_auth_user()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $authUser = User::factory()->create();

        $this->actingAs($admin);
    
        $this->assertCount(2, User::all());

        $response = $this->patch(route('updateUsers', $user->id), [
            'name'=> 'Update Name',
        ]);
    
        $this->assertEquals(User::first()->name,'Update Name');

        $this->actingAs($user);

        $response = $this->patch(route('updateUsers', $user->id), [
            'name'=> 'Update Name by user',
        ]);
    
        $this->assertEquals(User::first()->name,'Update Name');

    } 

}
