<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskIdRequest;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\EstimateTaskRequest;
use App\Http\Resources\TaskIdResource;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TaskCollection
     */
    public function index()
    {
        return new TaskCollection(Task::all());
    }

    /**
     * Display the specified resource.
     *
     * @param TaskIdRequest $request
     * @return TaskResource
     */
    public function show(TaskIdRequest $request)
    {
        $data = $request->validated();

        $task = Task::findByTaskIdOrFail($data['taskId']);

        return new TaskResource($task);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaskRequest $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function store(CreateTaskRequest $request)
    {
        $data = $request->validated();

        $task = Task::create([
            'title' => $data['Title'],
            'description' => $data['Description'],
        ]);

        return (new TaskIdResource($task))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Add estimation to a task.
     *
     * @param EstimateTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function estimate(EstimateTaskRequest $request)
    {
        $data = $request->validated();

        $task = Task::findByTaskIdOrFail($data['id']);

        $task->update([
            'estimation' => $data['estimation'],
        ]);

        return $this->respondApiSuccess();
    }

    /**
     * Close a task.
     *
     * @param TaskIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(TaskIdRequest $request)
    {
        $data = $request->validated();
        $task = Task::findByTaskIdOrFail($data['taskId']);

        $task->setStatusClosed();
        $task->save();

        return $this->respondApiSuccess();
    }
}
