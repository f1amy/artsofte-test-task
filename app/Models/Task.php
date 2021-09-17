<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
 * @property-read \App\Models\Sprint|null $sprint
 * @method static Builder|Task closed()
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

    public const REGEX_ID = '/^TASK-\d+$/';
    public const REGEX_ESTIMATION = '/^(((\d+\.)?\d+h)|(\d+m))$/';

    public const UNIT_HOUR = 'h';
    public const UNIT_MINUTE = 'm';

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
    ];

    /**
     * Set task status to open.
     */
    public function setStatusOpen(): void
    {
        $this->status = static::STATUS_OPEN;
    }

    /**
     * Set task status to closed.
     */
    public function setStatusClosed(): void
    {
        $this->status = static::STATUS_CLOSED;
    }

    /**
     * Get estimation in hours.
     *
     * @return float|null
     */
    public function estimationInHours(): ?float
    {
        if ($this->estimation) {
            $unit = substr($this->estimation, -1);
            $number = (float) substr($this->estimation, 0, -1);

            return $unit === static::UNIT_MINUTE
                ? round($number / 60, 2)
                : $number;
        }

        return null;
    }

    /**
     * Format Task ID for API.
     *
     * @return string
     */
    public function formatId(): string
    {
        return "TASK-{$this->id}";
    }

    /**
     * Parse Task ID.
     *
     * @param string $id
     * @return int|null
     */
    public static function parseId(string $id): ?int
    {
        if (Str::startsWith($id, 'TASK-')) {
            return substr($id, 5);
        }

        return null;
    }

    /**
     * Find model by Task ID.
     *
     * @param string $id
     * @return Task|Task[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public static function findByTaskIdOrFail(string $id)
    {
        $parsedId = static::parseId($id);

        return static::findOrFail($parsedId);
    }

    /**
     * Scope to only open tasks.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOpened(Builder $query)
    {
        return $query->where('status', static::STATUS_OPEN);
    }

    /**
     * Scope to only closed tasks.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeClosed(Builder $query)
    {
        return $query->where('status', static::STATUS_CLOSED);
    }

    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }
}
