<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        \App\Models\Admin::factory(10)->create();
        \App\Models\Admin::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->appOne();
        $this->appTwo();
    }

    public function appOne(): void
    {
        \App\Models\AppOne\AppOneUser::factory(10)->create();
        \App\Models\AppOne\AppOneUser::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        \App\Models\AppOne\AppOneAdmin::factory(10)->create();
        \App\Models\AppOne\AppOneAdmin::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function appTwo(): void
    {
        \App\Models\AppTwo\AppTwoUser::factory(10)->create();
        \App\Models\AppTwo\AppTwoUser::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        \App\Models\AppTwo\AppTwoAdmin::factory(10)->create();
        \App\Models\AppTwo\AppTwoAdmin::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
