<?php

namespace Database\Factories;

use App\Models\Sprint;
use Illuminate\Database\Eloquent\Factories\Factory;

class SprintFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sprint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'year' => $this->faker->year(),
            'week' => $this->faker->numberBetween(1, 51),
            'status' => $this->faker->randomElement([Sprint::STATUS_CREATED, Sprint::STATUS_CLOSED]),
        ];
    }

    /**
     * Set status to 'created'.
     *
     * @return SprintFactory
     */
    public function created()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Sprint::STATUS_CREATED,
            ];
        });
    }

    /**
     * Set status to 'started'.
     *
     * @return SprintFactory
     */
    public function started()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Sprint::STATUS_STARTED,
            ];
        });
    }

    /**
     * Set status to 'closed'.
     *
     * @return SprintFactory
     */
    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Sprint::STATUS_CLOSED,
            ];
        });
    }
}
