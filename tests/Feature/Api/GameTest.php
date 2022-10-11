<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class GameTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_no_teacher_no_change_play_permission() {
        $this->withoutExceptionHandling();

        Sanctum::actingAs(
            $teacher = Teacher::factory()->create([
                'isAdmin' => true
            ])
        );
    }
}
