<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    // login //
    public function test_user_can_authenticate()
    {
        $teacher = Teacher::factory()->create();

        $student = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $student->email,
            'password' => 'password'
        ])
            ->assertOk();

        $this->assertArrayHasKey('access_token', $response->json());
    }

    public function test_user_can_not_authenticate_with_invalid_password()
    {
        $teacher = Teacher::factory()->create();

        $student = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $student->email,
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(404);
    }

    public function test_user_can_not_authenticate_with_invalid_email()
    {
        $teacher = Teacher::factory()->create();

        $student = User::factory()->create([]);

        $response = $this->postJson(route('login'), [
            'email' => 'guiller@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(404);
    }
    // done //

    // logout 
    public function test_teacher_and_students_can_logout()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $response = $this->get(route('logout'));
        $response->assertStatus(200);
        $this->assertFalse($teacher === auth()->check());
    }
    // done //

    // register //
    public function test_no_auth_user_can_not_register()
    {
        $this->withoutExceptionHandling();

        $teacher = Teacher::factory()->create();

        $this->actingAs($teacher);

        $response = $this->attemptToRegister();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(Teacher::first());
        $this->assertCount(1, Teacher::all());
    }

    public function test_no_teacher_can_not_register_student()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => false
            ])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(Teacher::first());
        $this->assertCount(1, Teacher::all());
        $this->assertCount(0, User::all());
    }

    public function test_teacher_can_register_student()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(200);
        $this->assertAuthenticatedAs(Teacher::first());
        $this->assertCount(1, Teacher::all());
        $this->assertCount(1, User::all());
    }

    public function test_student_can_not_register_student()
    {
        $this->withoutExceptionHandling();

        $teacher = Teacher::factory()->create();

        Sanctum::actingAs(
            $student = User::factory()->create([])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(User::first());
        $this->assertCount(1, User::all());
    }

    public function test_director_can_not_register_student()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $director = Teacher::factory()->create([
                'superAdmin' => true
            ])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(Teacher::first());
        $this->assertCount(1, Teacher::all());
        $this->assertCount(0, User::all());
    }

    protected function attemptToRegister(array $params = [])
    {
        return $this->post(route('registerStudent'), array_merge([
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ], $params));
    }
    // done //

    // users list //
    public function test_user_no_auth_can_not_see_users_list()
    {
        $this->withoutExceptionHandling();

        $teacher = Teacher::factory()->create();

        $student = User::factory()->create();

        $this->actingAs($student);

        $response = $this->get(route('listStudents'));
        $response->assertStatus(401);
    }

    public function test_if_auth_user_no_teacher_can_not_see_users_list()
    {
        $this->withExceptionHandling();

        $teacher = Teacher::factory()->create();

        Sanctum::actingAs(
            $noTeacher = User::factory()->create([])
        );

        $this->actingAs($noTeacher);
        $response = $this->get(route('listStudents'));
        $response->assertStatus(401);
    }

    public function test_if_auth_user_teacher_can_see_users_list()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
        $teacher = Teacher::factory()->create([
            'isAdmin' => true
        ])
        );

        $noTeacher = User::factory()->create([]);
       
        $this->actingAs($teacher);
        $response = $this->get(route('listStudents'));
        $response->assertStatus(200);
    }
    // done //

    // user profile //
    public function test_user_no_auth_can_not_see_user_profile()
    {
        $this->withoutExceptionHandling();

        $teacher = Teacher::factory()->create([]);
             
        $student = User::factory()->create([]);

        $this->actingAs($teacher);
        $response = $this->get(route('profileStudent', $student->id));
        $response->assertStatus(401);

        $this->actingAs($student);
        $response = $this->get(route('profileStudent', $student->id));
        $response->assertStatus(401);
    }

    public function test_if_auth_user_no_teacher_can_not_see_user_profile()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );
       
        Sanctum::actingAs(
            $student = User::factory()->create([])
        );

        $this->actingAs($teacher);
        $response = $this->get(route('profileStudent', $student->id));
        $response->assertStatus(200);

        $this->actingAs($student);
        $response = $this->get(route('profileStudent', $student->id));
        $response->assertStatus(401);
    }
    // done //

    // delete //
    public function test_delete_user_no_auth_user()
    {
        $this->withoutExceptionHandling();

        $teacher = Teacher::factory()->create();

        $student = User::factory()->create();

        $this->actingAs($teacher);
        $response = $this->delete(route('deleteStudent', $student->id));
        $response->assertStatus(401);
    }

    public function test_delete_auth_user_no_teacher()
    {
        $this->withoutExceptionHandling();

        $teacher = Teacher::factory()->create();

        Sanctum::actingAs(
            $userNoTeacher = User::factory()->create([])
        );

        Sanctum::actingAs(
            $student = User::factory()->create([])
        );

        $this->actingAs($userNoTeacher);
        $response = $this->delete(route('deleteStudent', $student->id));
        $response->assertStatus(401);
    }

    public function test_delete_auth_user_teacher()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([]);

        $this->actingAs($teacher);
        $response = $this->delete(route('deleteStudent', $student->id));
        $response->assertStatus(200);
    }
    // done //

    // student owns teacher id //
    public function test_student_owns_teacher_id()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([]);

        $this->assertEquals($student->teacher_id, $teacher->id);
    }
    // done //

    // edit
    public function test_teacher_can_edit_student()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([
            'email' => 'test@gmail.com'
        ]);

        $this->actingAs($teacher);
        $response = $this->patch(route('updateStudent', $student->id), [
            'name' => 'Bad Name',
            'email' => 'kerim@gmail.com',
            'password' => 'password',
            'showPassword' => 'password'
        ]);
        $response->assertStatus(200);

        $student = $student->fresh();

        $this->assertEquals('kerim@gmail.com', $student->email);
    }

    public function test_no_teacher_can_not_edit_student()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $noTeacher = Teacher::factory()->create([
                'isAdmin' => false
            ])
        );

        $student = User::factory()->create([
            'email' => 'test@gmail.com'
        ]);

        $this->actingAs($noTeacher);
        $response = $this->patch(route('updateStudent', $student->id), [
            'name' => 'Bad Name',
            'email' => 'kerim@gmail.com',
            'password' => 'password',
            'showPassword' => 'password'
        ]);
        $response->assertStatus(401);

        $student = $student->fresh();

        $this->assertEquals('test@gmail.com', $student->email);
    }
    // done //
}
