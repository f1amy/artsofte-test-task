<?php

namespace App\Service;

use App\Models\Sprint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SprintService
{
    /**
     * Parse Sprint ID.
     *
     * @param mixed $id
     * @return array|null
     */
    public static function parseSprintId(mixed $id): ?array
    {
        if (preg_match(Sprint::REGEX_ID, $id)) {
            [$year, $week] = explode('-', $id);

            return compact('year', 'week');
        }

        return null;
    }

    /**
     * Find model by Sprint ID or fail.
     *
     * @param string $id
     * @return Sprint|Builder|Model
     */
    public static function findBySprintId(string $id)
    {
        $parsed = self::parseSprintId($id);

        return Sprint::whereWeek($parsed['week'])
            ->where('year', 'like', '%'.$parsed['year'])
            ->firstOrFail();
    }
}
