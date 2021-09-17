<?php

namespace App\Service;

use App\Models\Sprint;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SprintService
{
    public const REGEX_ID = '/^\d{2}-\d+$/';

    /**
     * Parse Sprint ID.
     *
     * @param mixed $id
     * @return array|null
     */
    private static function parseSprintId(mixed $id): ?array
    {
        if (preg_match(self::REGEX_ID, $id)) {
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
        $attrs = self::parseSprintId($id);

        return Sprint::whereWeek($attrs['week'])
            ->where('year', 'like', '%'.$attrs['year'])
            ->firstOrFail();
    }
}
