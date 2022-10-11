<?php

namespace Tests\Feature\Api;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;
    
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
    }

    public function test_teacher_change_play_permission() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );

        $student = User::factory()->create([]);

        $response = $this->patch(route('changePermission'), [
            'play_permission' => true
        ]);

        $response->assertStatus(200);

        $student->fresh();

        $this->assertEquals(true, $student->play_permission);
    }

    public function test_auth_can_get_permission() {
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

    public function test_no_auth_can_not_get_permission() {
        $this->withoutExceptionHandling();

        $teacher = Teacher::factory()->create([]);

        $student = User::factory()->create([
            'play_permission' => true
        ]);

        $this->actingAs($student);
        $response = $this->get(route('getPermission'));
        $response->assertStatus(200);
      
       /*  $this->assertFalse($student === auth()->check()); */
    }

    public function test_teacher_can_edit_play()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $teacher = User::factory()->create([
                'isAdmin' => true
            ])
        );

        $user = User::factory()->create([
            'isAdmin' => false,
            'superAdmin' => false,
            'teacher' => $teacher->id,
        ]);

        $response = $this->patch(route('play', $user->id), [
            'play' => 1
        ]);

        $response->assertStatus(200);

        $user = $user->fresh();

        $this->assertEquals(1, $user->play);
    }

    public function test_no_teacher_can_edit_play()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $teacher = User::factory()->create([
                'isAdmin' => false
            ])
        );

        $user = User::factory()->create([
            'isAdmin' => false,
            'superAdmin' => false,
            'teacher' => $teacher->id,
        ]);

        $response = $this->patch(route('play', $user->id), [
            'play' => 1
        ]);

        $response->assertStatus(401);

        $user = $user->fresh();

        $this->assertEquals(0, $user->play);
    }

    public function test_get_play_value()
    {
        $this->withExceptionHandling();

        Sanctum::actingAs(
            $student = User::factory()->create([
                'isAdmin' => false,
                'superAdmin' => false
            ])
        );

        $response = $this->get(route('playValue'));
        $response->assertStatus(200);

        $this->assertEquals(0, $student->play);
    }

}
