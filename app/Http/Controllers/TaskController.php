<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateTaskRequest $request
     * @return TaskResource
     */
    public function store(CreateTaskRequest $request)
    {
        $data = $request->validated();

        $task = Task::create([
            'title' => $data['Title'],
            'description' => $data['Description'],
        ]);

        return new TaskResource($task);
    }

    /**
     * Add estimation to a task.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function estimate(Request $request)
    {
        //
    }
}
