<?php

namespace App\Http\Resources;

class TaskResource extends TaskIdResource
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
        $task = $this->resource;

        return array_merge(parent::toArray($request), [
            'sprintId' => $this->when($task->sprint !== null,
                fn () => $task->sprint->formatSprintId()
            ),
            'Title' => $task->title,
            'Description' => $task->description,
            'Estimation' => $task->estimation,
            'EstimationInHours' => $task->estimationInHours(),
            'Status' => $task->status,
            'CreatedAt' => $task->created_at,
            'UpdatedAt' => $task->updated_at,
        ]);
    }
}
