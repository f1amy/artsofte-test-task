<?php

namespace App\Service;

use App\Models\Sprint;
use Illuminate\Support\Carbon;

class SprintValidationService
{
    private const MAX_DAYS_PRIOR_START = 7;
    private const MAX_TASKS_ESTIMATION = 40;

    private Sprint $sprint;

    public function setSprint(Sprint $sprint): void
    {
        $this->sprint = $sprint;
    }

    /**
     * Validate can start sprint.
     *
     * @return bool|string
     */
    public function validate()
    {
        // no other active sprints
        if ($this->otherActiveSprintsExist()) {
            return 'Other sprint already started.';
        }

        // all tasks must have estimation
        if ($this->tasksWithoutEstimationExist()) {
            return 'Not all associated tasks have estimation.';
        }

        // less or equal to 40 hours of tasks estimations combined
        if ($this->exceededMaxTasksEstimation()) {
            return 'Overall tasks estimation is over '
                . self::MAX_TASKS_ESTIMATION . ' hours.';
        }

        // less than 7 days before starting week
        if ($this->exceededMaxDaysUntilStartWeek()) {
            return 'It is over ' . self::MAX_DAYS_PRIOR_START
                . ' days before sprint start week.';
        }

        return true;
    }

    /**
     * Check if there are other active sprints besides this one.
     *
     * @return bool
     */
    public function otherActiveSprintsExist(): bool
    {
        return Sprint::started()
            ->where('id', '!=', $this->sprint->getKey())
            ->exists();
    }

    /**
     * Check if sprint has tasks without estimation.
     *
     * @return bool
     */
    public function tasksWithoutEstimationExist(): bool
    {
        return $this->sprint->tasks()->whereNull('estimation')->exists();
    }

    /**
     * Determine if current date less than max days until sprint starting week.
     *
     * @return bool
     */
    public function exceededMaxDaysUntilStartWeek(): bool
    {
        $startWeek = Carbon::createFromFormat('Y', $this->sprint->year);

        $startWeek->startOfYear();
        $startWeek->addWeeks($this->sprint->week);

        $daysDiff = now()->diffInDays($startWeek, false);

        return $daysDiff <= self::MAX_DAYS_PRIOR_START;
    }

    /**
     * Determine if the sprint has overall tasks estimation over limit.
     *
     * @return bool
     */
    public function exceededMaxTasksEstimation(): bool
    {
        return $this->sprint->calculateTasksEstimation() > self::MAX_TASKS_ESTIMATION;
    }
}
