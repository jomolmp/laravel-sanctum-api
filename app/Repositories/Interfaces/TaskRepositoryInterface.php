<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function getAllTasks(): Collection;

    public function createTask(array $data): Task;
}
