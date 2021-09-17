<?php

namespace Database\Factories;

use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sprint_id' => Sprint::factory(),
            'title' => $this->faker->unique()->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'estimation' => $this->faker->optional()->randomElement([
                $this->faker->regexify('/^\dh$/'),
                $this->faker->regexify('/^\d\dh$/'),
                $this->faker->regexify('/^\d\.\dh$/'),
                $this->faker->regexify('/^\d\d\.\dh$/'),
                $this->faker->regexify('/^\dm$/'),
                $this->faker->regexify('/^\d\dm$/'),
            ]),
            'status' => $this->faker->randomElement([Task::STATUS_OPEN, Task::STATUS_CLOSED]),
        ];
    }

    /**
     * Set custom estimation.
     *
     * @return TaskFactory
     */
    public function estimation(string $time)
    {
        return $this->state(function (array $attributes) use ($time) {
            return [
                'estimation' => $time,
            ];
        });
    }

    /**
     * Set status to 'open'.
     *
     * @return TaskFactory
     */
    public function opened()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Task::STATUS_OPEN,
            ];
        });
    }

    /**
     * Set status to 'closed'.
     *
     * @return TaskFactory
     */
    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Task::STATUS_CLOSED,
            ];
        });
    }
}
