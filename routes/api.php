<?php

use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.')->group(function () {
    Route::name('tasks.')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('list');
        Route::post('tasks', [TaskController::class, 'store'])->name('create');
        Route::post('estimate/task', [TaskController::class, 'estimate'])->name('estimate');
    });

    Route::name('sprints.')->group(function () {
        Route::get('sprints', [SprintController::class, 'index'])->name('list');
        Route::post('sprints', [SprintController::class, 'store'])->name('create');
        Route::post('sprints/add-task', [SprintController::class, 'addTask'])->name('add-task');
        Route::post('sprints/start', [SprintController::class, 'start'])->name('start');
        Route::post('sprints/close', [SprintController::class, 'close'])->name('close');
    });
});
