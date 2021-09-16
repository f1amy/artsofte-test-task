<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sprint
 *
 * @property int $id
 * @property int $year
 * @property int $week
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task[] $tasks
 * @property-read int|null $tasks_count
 * @method static \Database\Factories\SprintFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint whereWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sprint whereYear($value)
 * @mixin \Eloquent
 */
class Sprint extends Model
{
    use HasFactory;

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

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function formatId(): string
    {
        $year = substr($this->year, -2);

        return "{$year}-{$this->week}";
    }
}
