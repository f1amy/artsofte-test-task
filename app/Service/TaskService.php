<?php

namespace App\Service;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaskService
{
    /**
     * Parse Task ID from string.
     *
     * @param string $id
     * @return int|null
     */
    public static function parseTaskId(string $id): ?int
    {
        if (Str::startsWith($id, 'TASK-')) {
            return substr($id, 5);
        }

        return null;
    }

    /**
     * Find model by Task ID or fail.
     *
     * @param string $id
     * @return Task|Task[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public static function findByTaskId(string $id)
    {
        $parsedId = self::parseTaskId($id);

        return Task::findOrFail($parsedId);
    }
}
