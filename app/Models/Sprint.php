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
 * @property-read string $api_sprint_id
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
    public function getApiSprintIdAttribute(): string
    {
        $year = substr($this->year, -2);

        return "{$year}-{$this->week}";
    }

    /**
     * Calculate overall tasks estimation in hours.
     *
     * @return float
     */
    public function calculateTasksEstimation(): float
    {
        $estimation = $this->tasks->sum(function (Task $task) {
            return $task->getEstimationInHours() ?? 0.;
        });

        return round($estimation, 2);
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
