<?php

namespace App\Http\Controllers\Tasks;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepositoryInterface;
use App\Models\Task;

class TaskController extends Controller
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $tasks = $this->taskRepository->getTasksByUser($user);

        return response()->json([
            'message' => 'Tasks retrieved successfully',
            'tasks' => $tasks,
            'status' => 200,
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        $task = $this->taskRepository->findTask($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
                'status' => 404,
            ], 404);
        }

        if ($task->user_id != $user->id) {
            return response()->json([
                'message' => 'You are not authorized to view this task',
                'status' => 403,
            ], 403);
        }

        return response()->json([
            'message' => 'Task retrieved successfully',
            'task' => $task,
            'status' => 200,
        ]);
    }

    public function store(TaskRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['status_id'] = 1;
        $data['user_id'] = $user->id;
        $task = $this->taskRepository->createTask($data);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task,
            'status' => 200,
        ]);
    }

    public function update(TaskRequest $request, $id)
    {
        $user = auth()->user();
        $task = $this->taskRepository->findTask($id);
        $status = $request->get('status');

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
                'status' => 404,
            ]);
        }

        if ($task->user_id != $user->id) {
            return response()->json([
                'message' => 'You are not authorized to update this task',
                'status' => 403,
            ]);
        }

        if ($status == 'ongoing') {
            $task->status_id = 2;
        } elseif ($status == 'done') {
            $task->status_id = 3;
        }

        $this->taskRepository->updateTask($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task,
            'status' => 200,
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $task = $this->taskRepository->findTask($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
                'status' => 404,
            ]);
        }

        if ($task->user_id != $user->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this task',
                'status' => 403,
            ]);
        }

        $this->taskRepository->deleteTask($task);

        return response()->json([
            'message' => 'Task deleted successfully',
            'status' => 200,
        ]);
    }
}
