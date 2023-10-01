<?php

use App\Http\Controller\AuthController;
use App\Http\Controller\TaskController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('task')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:guardian'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/data', [AuthController::class, 'data']);
        Route::post('/refresh', [AuthController::class, 'refresh']);

        Route::get('/show_tasks', [TaskController::class, 'showTasks']);
        Route::post('/create_task', [TaskController::class, 'createTask']);
        Route::put('/update_task', [TaskController::class, 'updateTask']);

        // NOTE: lanjutkan tugas assignment di routing baru dibawah ini
        Route::delete('/delete_task/{id}', [TaskController::class, 'deleteTask']);
        Route::put('/assign_task', [TaskController::class, 'assignTask']);
        Route::delete('/unassign_task/{taskId}', [TaskController::class, 'unassignTask']);
        Route::put('/create_subtask', [TaskController::class, 'createSubtask']);
        Route::delete('/delete_subtask/{task_id}/{subtask_id}', [TaskController::class, 'deleteSubtask']);
    });
});
