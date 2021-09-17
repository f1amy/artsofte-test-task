<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Sprint
 *
 * @property int $id
 * @property int $year
 * @property int $week
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task[] $tasks
 * @property-read int|null $tasks_count
 * @method static \Database\Factories\SprintFactory factory(...$parameters)
 * @method static Builder|Sprint newModelQuery()
 * @method static Builder|Sprint newQuery()
 * @method static Builder|Sprint query()
 * @method static Builder|Sprint started()
 * @method static Builder|Sprint whereCreatedAt($value)
 * @method static Builder|Sprint whereId($value)
 * @method static Builder|Sprint whereStatus($value)
 * @method static Builder|Sprint whereUpdatedAt($value)
 * @method static Builder|Sprint whereWeek($value)
 * @method static Builder|Sprint whereYear($value)
 * @mixin \Eloquent
 */
class Sprint extends Model
{
    use HasFactory;

    public const REGEX_ID = '/^\d{2}-\d+$/';

    public const MAX_TASKS_ESTIMATION = 40;
    public const MAX_DAYS_PRIOR_START = 7;

    public const STATUS_CREATED = 'created';
    public const STATUS_STARTED = 'started';
    public const STATUS_CLOSED = 'closed';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'year',
        'week',
        'status',
    ];

    /**
     * Check can start the sprint.
     *
     * @return bool|string
     */
    public function canStart()
    {
        // no other active sprints
        if ($this->hasOtherActiveSprints()) {
            return 'Other sprint already started.';
        }

        // all tasks must have estimation
        if ($this->hasTasksWithoutEstimation()) {
            return 'Not all associated tasks have estimation.';
        }

        // less or equal to 40 hours of tasks estimations combined
        if ($this->hasOverMaxTasksEstimation()) {
            return 'Overall tasks estimation is over '
                . self::MAX_TASKS_ESTIMATION . ' hours.';
        }

        // less than 7 days before starting week
        if ($this->hasLessThanMaxDaysUntilStartWeek()) {
            return "It's over " . self::MAX_DAYS_PRIOR_START
                . 'days before sprint start week.';
        }

        return true;
    }

    /**
     * Check can close the sprint.
     *
     * @return bool
     */
    public function canClose(): bool
    {
        return $this->tasks()->opened()->doesntExist();
    }

    /**
     * Set sprint status to started.
     */
    public function setStatusStarted(): void
    {
        $this->status = self::STATUS_STARTED;
    }

    /**
     * Set sprint status to closed.
     */
    public function setStatusClosed(): void
    {
        $this->status = self::STATUS_CLOSED;
    }

    /**
     * Format Sprint ID for API.
     *
     * @return string
     */
    public function formatSprintId(): string
    {
        $year = substr($this->year, -2);

        return "{$year}-{$this->week}";
    }

    /**
     * Determine if the sprint has overall tasks estimation over limit.
     *
     * @return bool
     */
    private function hasOverMaxTasksEstimation(): bool
    {
        return $this->calculateTasksEstimation() > self::MAX_TASKS_ESTIMATION;
    }

    /**
     * Calculate overall tasks estimation in hours.
     *
     * @return float
     */
    public function calculateTasksEstimation(): float
    {
        $estimation = $this->tasks->sum(function (Task $task) {
            return $task->estimationInHours() ?? 0.;
        });

        return round($estimation, 2);
    }

    /**
     * Determine if current date less than max days until sprint starting week.
     *
     * @return bool
     */
    private function hasLessThanMaxDaysUntilStartWeek(): bool
    {
        $startWeek = Carbon::createFromFormat('Y', $this->year);
        $startWeek->startOfYear()
            ->addWeeks($this->week);

        $daysDiff = now()->diffInDays($startWeek, false);

        return $daysDiff <= self::MAX_DAYS_PRIOR_START;
    }

    /**
     * Check if sprint has tasks without estimation.
     *
     * @return bool
     */
    private function hasTasksWithoutEstimation(): bool
    {
        return $this->tasks()->whereNull('estimation')->exists();
    }

    /**
     * Check if there are other active sprints besides this one.
     *
     * @return bool
     */
    private function hasOtherActiveSprints(): bool
    {
        return self::started()
            ->where('id', '!=', $this->getKey())
            ->exists();
    }

    /**
     * Scope to only started sprints.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeStarted(Builder $query)
    {
        return $query->where('status', self::STATUS_STARTED);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
