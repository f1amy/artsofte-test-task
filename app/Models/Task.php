<?php

namespace App\Models;

use App\Service\TaskService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task
 *
 * @property int $id
 * @property int|null $sprint_id
 * @property string $title
 * @property string|null $description
 * @property string|null $estimation
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $api_task_id
 * @property-read \App\Models\Sprint|null $sprint
 * @method static \Database\Factories\TaskFactory factory(...$parameters)
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task opened()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereEstimation($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereSprintId($value)
 * @method static Builder|Task whereStatus($value)
 * @method static Builder|Task whereTitle($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'sprint_id',
        'title',
        'description',
        'estimation',
        'status',
    ];

    /**
     * Set task status to closed.
     */
    public function setStatusClosed(): void
    {
        $this->status = self::STATUS_CLOSED;
    }

    /**
     * Get estimation in hours.
     *
     * @return float|null
     */
    public function getEstimationInHours(): ?float
    {
        if ($this->estimation) {
            $unit = substr($this->estimation, -1);
            $number = (float) substr($this->estimation, 0, -1);

            if ($unit === TaskService::UNIT_MINUTE) {
                return round($number / 60, 2);
            }

            return $number;
        }

        return null;
    }

    /**
     * Format Task ID for API.
     *
     * @return string
     */
    public function getApiTaskIdAttribute(): string
    {
        return "TASK-{$this->id}";
    }

    /**
     * Scope to only open tasks.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOpened(Builder $query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }
}
