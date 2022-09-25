<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'name' => 'José Miguel',
            'email' => 'josemi@gmail.com',
            'superAdmin' => true
        ]);

        User::factory()->create([
            'name' => 'Kerim',
            'email' => 'kerim@gmail.com',
            'isAdmin' => true
        ]);

        User::factory()->create([
            'name' => 'Carla',
            'email' => 'carla@gmail.com',
            'isAdmin' => true
        ]);

        User::factory()->create([
            'name' => 'Miguel',
            'email' => 'miguel@gmail.com',
            'teacher' => 2
        ]);

        User::factory()->create([
            'name' => 'Luis',
            'email' => 'luis@gmail.com',
            'teacher' => 2
        ]);

        User::factory()->create([
            'name' => 'Juan',
            'email' => 'juan@gmail.com',
            'teacher' => 3
        ]);

        User::factory()->create([
            'name' => 'Roberto',
            'email' => 'roberto@gmail.com',
        ]);
    }
}
