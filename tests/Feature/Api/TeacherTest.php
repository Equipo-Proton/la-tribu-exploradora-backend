<?php

namespace Tests\Feature\Api;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase;
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

    public function test_no_director_can_not_register_teacher()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $director = Teacher::factory()->create([
                'superAdmin' => false
            ])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(Teacher::first());
        $this->assertCount(1, Teacher::all());
        $this->assertCount(0, User::all());
    }

    public function test_director_can_register_teacher()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $director = Teacher::factory()->create([
                'superAdmin' => true
            ])
        );

        $response = $this->attemptToRegister();

        $response->assertStatus(200);
        $this->assertAuthenticatedAs(Teacher::first());
        $this->assertCount(2, Teacher::all());
    }

    public function test_director_can_not_register_student()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $director = Teacher::factory()->create([
                'superAdmin' => true
            ])
        );

        $response = $this->attemptToRegisterStudent();

        $response->assertStatus(401);
        $this->assertAuthenticatedAs(Teacher::first());
        $this->assertCount(1, Teacher::all());
        $this->assertCount(0, User::all());
    }

    protected function attemptToRegister(array $params = [])
    {
        return $this->post(route('registerTeacher'), array_merge([
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ], $params));
    }

    protected function attemptToRegisterStudent(array $params = [])
    {
        return $this->post(route('registerStudent'), array_merge([
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ], $params));
    }
    // done //

     // teacher list tests passed
     public function test_user_no_auth_can_not_see_teachers_list()
     {
         $this->withoutExceptionHandling();

       
        $director = Teacher::factory()->create([
            'superAdmin' => true
        ]);
 
         $student = User::factory()->create();
     
         $this->actingAs($student);
 
         $response = $this->get(route('listTeachers'));
         $response->assertStatus(401);
     }
     
     public function test_if_auth_user_director_can__see_teachers_list()
     {
        $this->withoutExceptionHandling();

       Sanctum::actingAs(
        $director = Teacher::factory()->create([
            'superAdmin' => true
        ])
    );
         $student = User::factory()->create();
     
         $this->actingAs($director);
 
         $response = $this->get(route('listTeachers'));
         $response->assertStatus(200);
     } 
     // done //
 
    // user profile //
    public function test_user_no_director_can_not_see_teacher_profile()
    {
        $this->withoutExceptionHandling();

        $director = Teacher::factory()->create([
            'superAdmin' => false
        ]);
 
         $student = User::factory()->create();
     
        $this->actingAs($director);
         $response = $this->get(route('profileTeacher', $student->id));
         $response->assertStatus(401);
    }

    public function test_if_auth_user_director_can__see_teacher_profile()
    {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
        $director = Teacher::factory()->create([
            'superAdmin' => true
        ])
    );
         $student = User::factory()->create();
     
         $this->actingAs($director);
         $response = $this->get(route('profileTeacher', $student->id));
         $response->assertStatus(200);
    }
    // done //

    // delete //
    public function test_no_director_no_delete_teacher() {
        $this->withoutExceptionHandling();

       
            $directorFalse = Teacher::factory()->create([
                 'superAdmin' => false
            ]);

            $directorTrue = Teacher::factory()->create([
                'superAdmin' => true
           ]);
      

        $teacher = Teacher::factory()->create();
    
        $this->actingAs($directorFalse);

        $response = $this->delete(route('deleteTeacher', $teacher->id));
        $response->assertStatus(401);

        $this->actingAs($directorTrue);

        $response = $this->delete(route('deleteTeacher', $teacher->id));
        $response->assertStatus(200);
    }


    public function test_student_can_not_delete_teacher() {
        $this->withoutExceptionHandling();

        $director = Teacher::factory()->create([
            'superAdmin' => true
        ]);

        $teacher = Teacher::factory()->create([
            'superAdmin' => false
        ]);

        $student = User::factory()->create([]);

        $this->actingAs($student);
        $response = $this->delete(route('deleteTeacher', $teacher->id));
        $response->assertStatus(401);
    }
    // done //

    // edit //  
    public function test_director_can_edit_teacher() 
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $director = Teacher::factory()->create([
                'superAdmin' => true
            ])
        );

        $teacher = Teacher::factory()->create([
            'email' => 'test@gmail.com'
        ]);
        
        $response = $this->patch(route('updateTeacher', $teacher->id), [
            'name' => 'New Name',
            'email' => 'kerim@gmail.com',
            'password' => 'password',
            'showPassword' => 'password'
        ]);

        $response->assertStatus(200);

        $teacher = $teacher->fresh();

       
        $this->assertEquals('kerim@gmail.com', $teacher->email);
    }
    
    public function test_no_director_can_not_edit_teacher() 
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'superAdmin' => false
            ])
        );

        $teacher = Teacher::factory()->create([
            'email' => 'guille@gmail.com'
        ]);
        
        $response = $this->patch(route('updateTeacher', $teacher->id), [
            'name' => 'New Name',
            'email' => 'kerim@gmail.com',
            'password' => 'password',
            'showPassword' => 'password'
        ]);

        $response->assertStatus(401);

        $teacher = $teacher->fresh();

        $this->assertEquals('guille@gmail.com', $teacher->email);
    }    
    // done //
}
