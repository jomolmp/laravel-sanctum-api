<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks(): Collection
    {
        return Task::all();
    }

    public function createTask(array $data): Task
    {
        $task = new Task();
        $task->setAttribute('name', $data['name']);
        $task->setAttribute('status', $data['status']);
        $task->setAttribute('description', $data['description']);

        $task->save();

        return $task;
    }
}
