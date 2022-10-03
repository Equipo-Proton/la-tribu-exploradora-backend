<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    // register tests passed
    public function test_director_no_auth_can_not_register() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
    
        $this->actingAs($user);

        $response = $this->attemptToRegisterTeacher();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(User::first());
        $this->assertCount(1, User::all());
    }

    public function test_no_director_can_not_register()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $authUser = User::factory()->create([
                   'superAdmin' => false
            ])
        ); 

        $response = $this->attemptToRegisterTeacher();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(User::first());
        $this->assertCount(1, User::all());
    }

    public function test_director_can_register()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $authUser = User::factory()->create([
                   'superAdmin' => true
            ])
        ); 

        $response = $this->attemptToRegisterTeacher();

        $response->assertStatus(200);
        $this->assertAuthenticatedAs(User::first());
        $this->assertCount(2, User::all());
    }

    protected function attemptToRegisterTeacher(array $params = [])
    {
        return $this->post(route('teacherRegister'), array_merge([
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ], $params));
    }

     // teacher list tests passed
     public function test_user_no_auth_can_not_see_teachers_list()
     {
         $this->withoutExceptionHandling();
 
         $user = User::factory()->create();
     
         $this->actingAs($user);
 
         $response = $this->get('/api/teachers');
         $response->assertStatus(401);
     }
     
     public function test_if_auth_user_no_director_can_not_see_teachers_list()
     {
         $this->withExceptionHandling();
 
         User::factory()->create();
 
         Sanctum::actingAs(
             $user = User::factory()->create([
                    'superAdmin' => false
             ])
         ); 
 
 
         $response = $this->get('/api/teachers');        
         $response->assertStatus(401);
     } 
 
     public function test_if_auth_user_director_can_see_teachers_list()
     {
         $this->withExceptionHandling();
 
         User::factory()->create([
            'isAdmin' => true
         ]);

         User::factory()->create([
            'isAdmin' => false
         ]);
 
         Sanctum::actingAs(
             $user = User::factory()->create([
                    'superAdmin' => true
             ])
         ); 
 
         $users = User::all();
 
         $response = $this->get('/api/teachers');        
         $response->assertStatus(200);
         $this->assertCount(1, User::all()->where('isAdmin', '=', true));
     } 

    // user profile tests passed
    public function test_user_no_auth_can_not_see_teacher_profile()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $currentUser = User::factory()->create();
    
        $this->actingAs($currentUser);

        $response = $this->get(route('profile', $user->id));
        $response->assertStatus(401);
    }

    public function test_if_auth_user_no_director_can_not_see_teacher_profile()
    {
        $this->withExceptionHandling();

        $user = User::factory()->create([
            'isAdmin' => true
        ]);

        Sanctum::actingAs(
            $user = User::factory()->create([
                   'superAdmin' => false
            ])
        ); 


        $response = $this->get(route('profile', $user->id));        
        $response->assertStatus(401);
    }

    public function test_if_auth_user_director_can_see_teacher_profile()
    {
        $this->withExceptionHandling();

        $teacher = User::factory()->create([
            'isAdmin' => true
        ]);

        User::factory()->create([
            'isAdmin' => false
        ]);

        Sanctum::actingAs(
            $user = User::factory()->create([
                   'superAdmin' => true
            ])
        ); 

        $response = $this->get(route('profile', $teacher->id));        
        $response->assertStatus(200);
    } 

    // delete tests passed
    public function test_delete_teacher_no_auth_user() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
    
        $this->actingAs($user);

        $response = $this->delete(route('deleteTeacher', $user->id));
        $response->assertStatus(401);
    }

    public function test_delete_teacher__auth_user_no_director() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'isAdmin' => true
        ]);

        Sanctum::actingAs(
            $userNoTeacher = User::factory()->create([
                   'superAdmin' => false
            ])
        ); 

        $response = $this->delete(route('deleteTeacher', $user->id));
        $response->assertStatus(401);
    }

    public function test_delete_user__auth_user_teacher() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create([
            'isAdmin' => true
        ]);

        Sanctum::actingAs(
            $userTeacher = User::factory()->create([
                   'superAdmin' => true
            ])
        ); 

        $response = $this->delete(route('deleteTeacher', $user->id));
        $response->assertStatus(200);
    }

    
    public function test_admin_can_edit_teacher() 
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $admin = User::factory()->create([
                'superAdmin' => true
            ])
        );

        $teacher = User::factory()->create([
            'isAdmin' => true,
            'superAdmin' => false,
            'email' => 'test@gmail.com'
        ]);
        
        $response = $this->patch(route('updateTeacher', $teacher->id), [
            'name' => 'New Name',
            'email' => $teacher->email,
            'password' => 'password',
            'showPassword' => 'password'
        ]);

        $response->assertStatus(200);

        $teacher = $teacher->fresh();

        $this->assertEquals('New Name', $teacher->name);
        $this->assertEquals('test@gmail.com', $teacher->email);
    }
    
    public function test_no_admin_can_not_edit_teacher() 
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $noAdmin = User::factory()->create([
                'superAdmin' => false
            ])
        );

        $teacher = User::factory()->create([
            'name'  => 'Guillermo',
            'isAdmin' => true,
            'superAdmin' => false,
            'email' => 'guille@gmail.com'
        ]);
        
        $response = $this->patch(route('updateTeacher', $teacher->id), [
            'name' => 'New Name',
            'email' => $teacher->email,
            'password' => 'password',
            'showPassword' => 'password'
        ]);

        $response->assertStatus(401);

        $teacher = $teacher->fresh();

        $this->assertEquals('Guillermo', $teacher->name);
        $this->assertEquals('guille@gmail.com', $teacher->email);
    }    
}
