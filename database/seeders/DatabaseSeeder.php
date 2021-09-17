<?php

namespace Database\Seeders;

use App\Models\Sprint;
use App\Models\Task;
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
        Sprint::factory()->count(5)->create();
        Task::factory()
            ->count(10)
            ->state([
                'sprint_id' => null,
            ])
            ->create();

        Task::factory()
            ->closed()
            ->for(Sprint::factory()->closed())
            ->count(5)
            ->create();

        Sprint::factory()
            ->created()
            ->has(Task::factory()->count(3))
            ->create();

        $sprint = Sprint::factory()
            ->started()
            ->state([
                'year' => 2023,
                'week' => 12,
            ]);

        Task::factory()
            ->closed()
            ->for($sprint)
            ->count(8)
            ->estimation('1h')
            ->create();
    }
}
