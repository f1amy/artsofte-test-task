<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSprintRequest;
use App\Http\Resources\SprintResource;
use App\Models\Sprint;
use Illuminate\Http\Request;

class SprintController extends Controller
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
     * @param CreateSprintRequest $request
     * @return SprintResource
     */
    public function store(CreateSprintRequest $request)
    {
        $data = $request->validated();

        $sprint = Sprint::create([
            'year' => $data['Year'],
            'week' => $data['Week'],
        ]);

        return new SprintResource($sprint);
    }

    /**
     * Add task to a sprint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addTask(Request $request)
    {
        //
    }

    /**
     * Start a sprint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request)
    {
        //
    }

    /**
     * Close a sprint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request)
    {
        //
    }
}
