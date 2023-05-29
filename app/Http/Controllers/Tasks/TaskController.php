<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

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

        return TaskResource::collection($tasks)
            ->additional([
                'message' => 'Tasks retrieved successfully',
            ]);
    }

    public function show($id)
    {
        $task = $this->taskRepository->findTask($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
                'status' => 404,
            ]);
        }

        try {
            $this->authorize('view', $task);

            return (new TaskResource($task))
                ->additional([
                    'message' => 'Task retrieved successfully',
                    'status' => 200,
                ]);
        } catch (AuthorizationException $e) {

            return response()->json([
                'message' => 'You are not authorized to view this task',
                'error' => $e->getMessage(),
                'status' => 403,
            ]);
        }
    }

    public function store(TaskRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['status_id'] = 1;
        $data['user_id'] = $user->id;
        $task = $this->taskRepository->createTask($data);

        return (new TaskResource($task))
            ->additional([
                'message' => 'Task created successfully',
            ]);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = $this->taskRepository->findTask($id);
        $status = $request->get('status');

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
                'status' => 404,
            ]);
        }

        try {
            $this->authorize('update', $task);

            if ($status == 'ongoing') {
                $task->status_id = 2;
            } elseif ($status == 'done') {
                $task->status_id = 3;
            }

            $this->taskRepository->updateTask($task, $request->validated());

            return (new TaskResource($task))
                ->additional([
                    'message' => 'Task updated successfully',
                ]);
        } catch (AuthorizationException $e) {

            return response()->json([
                'message' => 'You are not authorized to update this task',
                'error' => $e->getMessage(),
                'status' => 403,
            ]);
        }
    }

    public function destroy($id)
    {
        $task = $this->taskRepository->findTask($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
                'status' => 404,
            ]);
        }

        try {
            $this->authorize('delete', $task);
            $this->taskRepository->deleteTask($task);

            return response()->json([
                'message' => 'Task deleted successfully',
                'status' => 200,
            ]);
        } catch (AuthorizationException $e) {

            return response()->json([
                'message' => 'You are not authorized to delete this task',
                'error' => $e->getMessage(),
                'status' => 403,
            ]);
        }
    }
}
