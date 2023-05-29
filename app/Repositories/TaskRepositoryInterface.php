<?php

namespace App\Repositories;

interface TaskRepositoryInterface
{
    public function getTasksByUser($user);
    public function findTask($id);
    public function createTask($data);
    public function updateTask($task, $data);
    public function deleteTask($task);
}
