<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Teacher::factory()->create([
            'name' => 'Amara',
            'email' => 'amara@gmail.com',
            'superAdmin' => true
        ]);

        Teacher::factory()->create([
            'name' => 'Carla',
            'email' => 'carla@gmail.com',
            'isAdmin' => true
        ]);

        Teacher::factory()->create([
            'name' => 'José Miguel',
            'email' => 'josemi@gmail.com',
            'isAdmin' => true
        ]);

        User::factory()->create([
            'name' => 'Miguel',
            'email' => 'miguel@gmail.com',
            'teacher_id' => 2
        ]);

        User::factory()->create([
            'name' => 'Inma',
            'email' => 'inma@gmail.com',
            'teacher_id' => 2
        ]);

        User::factory()->create([
            'name' => 'Kerim',
            'email' => 'kerim@gmail.com',
            'teacher_id' => 3
        ]);

        User::factory()->create([
            'name' => 'Mario',
            'email' => 'mario@gmail.com',
            'teacher_id' => 3
        ]);

        User::factory()->create([
            'name' => 'Buda',
            'email' => 'buda@gmail.com',
            'teacher_id' => 3
        ]);

        User::factory()->create([
            'name' => 'Guiller',
            'email' => 'guiller@gmail.com',
            'teacher_id' => 3
        ]);
    }
}
