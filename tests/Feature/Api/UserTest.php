<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // register tests passed
    public function test_user_no_auth_can_not_register()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->attemptToRegister();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(User::first());
        $this->assertCount(1, User::all());
    }

    public function test_no_teacher_can_not_register()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $authUser = User::factory()->create([
                'isAdmin' => false
            ])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(User::first());
        $this->assertCount(1, User::all());
    }

    public function test_teacher_can_register()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $authUser = User::factory()->create([
                'isAdmin' => true
            ])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(200);
        $this->assertAuthenticatedAs(User::first());
        $this->assertCount(2, User::all());
    }


    protected function attemptToRegister(array $params = [])
    {
        return $this->post(route('register'), array_merge([
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ], $params));
    }

    // log in tests passed
    public function test_users_can_authenticate()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertOk();

        $this->assertArrayHasKey('access_token', $response->json());
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(404);
    }

    // log out test passed
    public function test_user_can_logout()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $user = User::factory()->create([])
        );


        $response = $this->get('/api/logout');
        $response->assertStatus(200);
    }


    // users list tests passed
    public function test_user_no_auth_can_not_see_users_list()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/api/users');
        $response->assertStatus(401);
    }

    public function test_if_auth_user_no_teacher_can_not_see_users_list()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $noTeacher = User::factory()->create([
                'email' => 'test@gmail.com',
                'isAdmin' => false
            ])
        );

        $this->actingAs($noTeacher);
        $user = User::factory()->create([
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'teacher' => $noTeacher->id
        ]);

        $response = $this->get('/api/users');
        $response->assertStatus(401);
        $this->assertEquals($user->teacher, $noTeacher->id);
    }

    public function test_if_auth_user_teacher_can_see_users_list()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $teacher = User::factory()->create([
                'email' => 'test@gmail.com',
                'isAdmin' => true
            ])
        );

        $user = User::factory()->create([
            'name' => 'John',
            'email' => 'john@gmail.com',
            'teacher' => $teacher->id
        ]);

        $response = $this->get('/api/users');
        $response->assertStatus(200);
        $this->assertEquals($user->teacher, $teacher->id);
    }

    // user profile tests passed
    public function test_user_no_auth_can_not_see_user_profile()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $currentUser = User::factory()->create();

        $this->actingAs($currentUser);

        $response = $this->get(route('userProfile', $user->id));
        $response->assertStatus(401);
    }

    public function test_if_auth_user_no_teacher_can_not_see_user_profile()
    {
        $this->withExceptionHandling();

        User::factory()->create();

        Sanctum::actingAs(
            $user = User::factory()->create([
                'isAdmin' => false
            ])
        );

        $response = $this->get(route('userProfile', $user->id));
        $response->assertStatus(401);
    }

    public function test_if_auth_user_teacher_can_see_user_profile()
    {
        $this->withExceptionHandling();



        Sanctum::actingAs(
            $userTeacher = User::factory()->create([
                'email' => 'test@gmail.com',
                'isAdmin' => true
            ])
        );
        $user = User::factory()->create([
            'teacher' => $userTeacher->id,
        ]);

        $response = $this->get(route('userProfile', $user->id));
        $response->assertStatus(200);
        $this->assertCount(1, User::all()
            ->where('isAdmin', '=', false)
            ->where('superAdmin', '=', false)
            ->where('teacher', '=', $userTeacher->id));
    }

    // delete user tests passed
    public function test_delete_user_no_auth_user()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->delete(route('deleteUser', $user->id));
        $response->assertStatus(401);
    }

    public function test_delete_user__auth_user_no_teacher()
    {
        $this->withoutExceptionHandling();



        Sanctum::actingAs(
            $userNoTeacher = User::factory()->create([
                'email' => 'test@gmail.com',
                'isAdmin' => false
            ])
        );
        $user = User::factory()->create([
            'teacher' => $userNoTeacher->id
        ]);

        $response = $this->delete(route('deleteUser', $user->id));
        $response->assertStatus(401);
    }

    public function test_delete_user__auth_user_teacher()
    {
        $this->withoutExceptionHandling();



        Sanctum::actingAs(
            $userTeacher = User::factory()->create([
                'email' => 'test@gmail.com',
                'isAdmin' => true
            ])
        );
        $user = User::factory()->create([
            'teacher' => $userTeacher->id
        ]);

        $response = $this->delete(route('deleteUser', $user->id));
        $response->assertStatus(200);
    }




    public function test_when_teacher_create_student_in_teacher_column_appear_id()
    {

        $this->withExceptionHandling();

        Sanctum::actingAs(
            $teacher = User::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([
            'isAdmin' => false,
            'teacher' => $teacher->id
        ]);

        $response = $this->assertEquals($student->teacher, $teacher->id);
    }
    //prueba
    public function test_teacher_can_edit_student() 
    {
        $this->withExceptionHandling();

        $user = User::factory()->create([
            'isAdmin' => false,
            'name' => 'Mario'
        ]);

        Sanctum::actingAs(
            $teacher = User::factory()->create([
                'isAdmin' => true
            ])
        );
        
        $user->name = 'marta';

        $user->update();
        
        $response = $this->patch(route('update', $user->id ));
        
        $this->assertEquals($user->name, 'marta');
    }   
}
