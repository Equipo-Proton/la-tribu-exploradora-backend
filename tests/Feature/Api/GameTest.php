<?php

namespace Tests\Feature\Api;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;
    
    // play permission
    public function test_no_teacher_no_change_play_permission() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => false
            ])
        );

        $student = User::factory()->create([]);

        $response = $this->patch(route('changePermission'), [
            'play_permission' => true
        ]);

        $response->assertStatus(401);

        $student = $student->fresh();

        $this->assertEquals(false, $student->play_permission);
        $this->assertCount(1, User::all());
    }

   /*  public function test_teacher_change_play_permission() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([]);

        $this->actingAs($teacher);
        $response = $this->patch(route('changePermission'), [
            'play_permission' => true
        ]);

        $response->assertStatus(200);

        $student->fresh();

        $this->assertEquals(true, $student->play_permission);
    } */

    public function test_auth_user_can_get_permission() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([])
        );

        Sanctum::actingAs(
            $student = User::factory()->create([
                'play_permission' => true
            ])
        );

        $response = $this->get(route('getPermission'));
        $response->assertStatus(200);

        $this->assertEquals(true, $student->play_permission);
    }

    // correction
    public function test_auth_user_can_get_correction() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([])
        );

        Sanctum::actingAs(
            $student = User::factory()->create([
                'correction' => true
            ])
        );

        $response = $this->get(route('getCorrection'));
        $response->assertStatus(200);

        $this->assertEquals(true, $student->correction);
    }

   /*  public function test_teacher_send_correction() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([
            'teacher_id' => $teacher->id,
            'correction' => null
        ]);

        $response = $this->patch(route('sendCorrection', $student->id), [
            'correction' => true
        ]);

        $response->assertStatus(200);

        $student->fresh();

        $this->assertEquals(true, $student->correction);
    } */

    // show
    public function test_auth_user_can_get_show() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([])
        );

        Sanctum::actingAs(
            $student = User::factory()->create([
                'show' => true
            ])
        );

        $response = $this->get(route('getCorrection'));
        $response->assertStatus(200);

        $this->assertEquals(true, $student->show);
    }
  
   /*  public function test_teacher_send_correction() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([
            'show' => false,
            'teacher_id' => 1
        ]);

        $response = $this->patch(route('show'), [
            'show' => true
        ]);

        $response->assertStatus(200);

        $student->fresh();

        $this->assertEquals(true, $student->show);
    }  */

}
