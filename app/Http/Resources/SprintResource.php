<?php

namespace App\Http\Resources;

class SprintResource extends SprintIdResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $sprint = $this->resource;

        return array_merge(parent::toArray($request), [
            'Year' => $sprint->year,
            'Week' => $sprint->week,
            'Status' => $sprint->status,
            'TasksEstimation' => $sprint->calculateTasksEstimation() . 'h',
            'Tasks' => new TaskCollection($sprint->tasks),
            'CreatedAt' => $sprint->created_at,
            'UpdatedAt' => $sprint->updated_at,
        ]);
    }
}
