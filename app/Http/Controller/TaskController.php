<?php

namespace App\Http\Controller;

use App\ContohBootcamp\Services\TaskService;
use App\Helpers\MongoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller {
	private TaskService $taskService;
	public function __construct() {
		$this->taskService = new TaskService();
	}

	public function showTasks()
	{
		$tasks = $this->taskService->getTasks();
		return response()->json($tasks);
	}

	public function createTask(Request $request)
	{
		$request->validate([
			'title'=>'required|string|min:3',
			'description'=>'required|string'
		]);

		$data = [
			'title'=>$request->post('title'),
			'description'=>$request->post('description')
		];

		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->taskService->addTask($dataSaved);
		$task = $this->taskService->getById($id);

		return response()->json([
			"message" => "subtask added successfully",
			"data" => $task
		],200);
	}


	public function updateTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required|string',
			'title'=>'string',
			'description'=>'string',
			'assigned'=>'string',
			'subtasks'=>'array',
		]);

		$taskId = $request->post('task_id');
		$formData = $request->only('title', 'description', 'assigned', 'subtasks');
		$task = $this->taskService->getById($taskId);

		$this->taskService->updateTask($task, $formData);

		// $task = $this->taskService->getById($taskId);

		return response()->json([
			"message" => "subtask added successfully",
			"data" => $task
		],200);
	}


	// TODO: deleteTask()
	// URl: http://localhost:8001/task/delete_task/{id}
	public function deleteTask($id)
	{
		$existTask = $this->taskService->getById($id);

		if(!$existTask)	
		{
			return response()->json([
				"message"=> "Task ".$id." not found"
			], 401);
		}

		$this->taskService->deleteTask($existTask);

		return response()->json([
			'message'=> 'Success delete task '.$id
		], 200);
	}

	// TODO: assignTask()
	public function assignTask(Request $request)
	{
		$formData = $request->validate([
			'task_id'=>'required',
			'assigned'=>'required'
		]);
		
		$existTask = $this->taskService->getById($formData["task_id"]);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$formData["task_id"]." not found"
			], 401);
		}

		$this->taskService->signTask($formData["assigned"], $existTask);
		$task = $this->taskService->getById($formData["task_id"]);

		return response()->json([
			"message" => "subtask added successfully",
			"data" => $task
		],200);
	}

	// TODO: unassignTask()
	// URl: http://localhost:8001/unassign_task/{taskId}
	public function unassignTask($taskId)
	{
		$existTask = $this->taskService->getById($taskId);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$taskId." not found"
			], 401);
		}

		$this->taskService->signTask(null, $existTask);
		$task = $this->taskService->getById($taskId);

		return response()->json([
			"message" => "subtask added successfully",
			"data" => $task
		],200);
	}

	// TODO: createSubtask()
	public function createSubtask(Request $request)
	{
		$form = $request->validate([
			'task_id'=>'required',
			'title'=>'required|string',
			'description'=>'required|string'
		]);

		$existTask = $this->taskService->getById($form["task_id"]);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		}

		$this->taskService->addSubTask($existTask, $form);

		$task = $this->taskService->getById($form["task_id"]);

		return response()->json([
			"message" => "subtask added successfully",
			"data" => $task
		],200);
	}

	// TODO deleteSubTask()
	// URl: http://localhost:8001/delete_subtask/{task_id}/{subtask_id}
	public function deleteSubtask($task_id, $subtask_id)
	{
		$existTask = $this->taskService->getById($task_id);

		if(!$existTask)
		{
			return response()->json([
				"message"=> "Task ".$taskId." tidak ada"
			], 401);
		}

		$this->taskService->deleteSubTask($existTask, $subtask_id);

		$task = $this->taskService->getById($task_id);

		return response()->json([
			"message" => "subtask added successfully",
			"data" => $task
		],200);
	}

}