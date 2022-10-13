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
            'play_permission' => 1
        ]);

        $response->assertStatus(401);

        $student = $student->fresh();

        $this->assertEquals(false, $student->play_permission);
        $this->assertCount(1, User::all());
    }

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

        $this->actingAs($teacher);
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

        $this->actingAs($teacher);
        $response = $this->get(route('getCorrection'));
        $response->assertStatus(200);

        $this->assertEquals(true, $student->correction);
    }

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

        $response = $this->get(route('getShow'));
        $response->assertStatus(200);

        $this->assertEquals(true, $student->show);

        $this->actingAs($teacher);
        $response = $this->get(route('getShow'));
        $response->assertStatus(200);

        $this->assertEquals(true, $student->show);
    }
}
