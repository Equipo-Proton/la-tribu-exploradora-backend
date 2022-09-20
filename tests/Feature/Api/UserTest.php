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
    
    public function test_if_auth_user_no_teacher_can_not_see_users_list()
    {
        $this->withExceptionHandling();

        User::factory()->create();

        Sanctum::actingAs(
            $user = User::factory()->create([
                   'isAdmin' => false
            ])
        ); 


        $response = $this->get('/api/users');        
        $response->assertStatus(401);
    } 

    public function test_if_auth_user_teacher_can_see_users_list()
    {
        $this->withExceptionHandling();

        User::factory()->create();

        Sanctum::actingAs(
            $user = User::factory()->create([
                   'isAdmin' => true
            ])
        ); 

        $users = User::all();

        $response = $this->get('/api/users');        
        $response->assertStatus(200);
            
    } 


    public function test_user_no_auth_can_not_see_users_list()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
    
        $this->actingAs($user);

        $response = $this->get('/api/users');
        $response->assertStatus(401);
    }

    /* public function test_user_profile_can_be_updated_by_auth_user()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $authUser = User::factory()->create();

        $this->actingAs($authUser);
    
        $this->assertCount(2, User::all());

        $response = $this->patch(route('update', $user->id), [
            'name' => 'Update Name',
            'email' => 'user@gmail.com',
            'password' => 'password'
        ]);
    
        $this->assertEquals(User::first()->name,'Update Name');

    } 
 */
}
