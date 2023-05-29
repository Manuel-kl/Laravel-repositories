<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function getTasksByUser($user)
    {
        return Task::where('user_id', $user->id)->get();
    }

    public function findTask($id)
    {
        return Task::find($id);
    }

    public function createTask($data)
    {
        return Task::create($data);
    }

    public function updateTask($task, $data)
    {
        $task->update($data);
    }

    public function deleteTask($task)
    {
        $task->delete();
    }
}
