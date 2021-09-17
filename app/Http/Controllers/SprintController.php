<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSprintTaskRequest;
use App\Http\Requests\CreateSprintRequest;
use App\Http\Requests\SprintIdRequest;
use App\Http\Resources\SprintCollection;
use App\Http\Resources\SprintIdResource;
use App\Http\Resources\SprintResource;
use App\Models\Sprint;
use App\Service\SprintService;
use App\Service\SprintValidationService;
use App\Service\TaskService;

class SprintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return SprintCollection
     */
    public function index()
    {
        return new SprintCollection(Sprint::all());
    }

    /**
     * Display the specified resource.
     *
     * @param SprintIdRequest $request
     * @return SprintResource
     */
    public function show(SprintIdRequest $request)
    {
        $data = $request->validated();

        $sprint = SprintService::findBySprintId($data['sprintId']);

        return new SprintResource($sprint);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSprintRequest $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function store(CreateSprintRequest $request)
    {
        $data = $request->validated();

        $sprint = Sprint::create([
            'year' => $data['Year'],
            'week' => $data['Week'],
        ]);

        return (new SprintIdResource($sprint))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Add task to a sprint.
     *
     * @param AddSprintTaskRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTask(AddSprintTaskRequest $request)
    {
        $data = $request->validated();

        $sprint = SprintService::findBySprintId($data['sprintId']);
        $task = TaskService::findByTaskId($data['taskId']);

        $task->sprint()->associate($sprint);
        $task->save();

        return $this->respondApiSuccess();
    }

    /**
     * Start a sprint.
     *
     * @param SprintIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function start(SprintIdRequest $request)
    {
        $data = $request->validated();
        $sprint = SprintService::findBySprintId($data['sprintId']);

        $message = $this->canStartSprint($sprint);
        if ($message === true) {
            $sprint->setStatusStarted();
            $sprint->save();

            return response()->json([
                'Message' => 'Success.',
            ]);
        }

        return $this->respondApiError($message);
    }

    /**
     * Close a sprint.
     *
     * @param SprintIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(SprintIdRequest $request)
    {
        $data = $request->validated();
        $sprint = SprintService::findBySprintId($data['sprintId']);

        if ($this->canCloseSprint($sprint)) {
            $sprint->setStatusClosed();
            $sprint->save();

            return response()->json([
                'Message' => 'Success.',
            ]);
        }

        return $this->respondApiError('Not all tasks were closed.');
    }

    /**
     * Check if can start the sprint.
     *
     * @return bool|string
     */
    public function canStartSprint(Sprint $sprint)
    {
        $validator = new SprintValidationService();

        $validator->setSprint($sprint);

        return $validator->validate();
    }

    /**
     * Check if can close the sprint.
     *
     * @param Sprint $sprint
     * @return bool
     */
    public function canCloseSprint(Sprint $sprint): bool
    {
        return $sprint->tasks()->opened()->doesntExist();
    }
}
