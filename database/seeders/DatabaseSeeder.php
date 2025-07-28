<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Modules\Task\Models\Task;
use Modules\User\Models\User;
use Database\Factories\TaskFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Project\Models\Project;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => time()
        ]);

        Project::factory()
            ->count(30)
            ->has(Task::factory(30), 'tasks')
            ->create();
    }
}
