<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function getAllTasks(): Collection;
    public function createTask(array $data):Task;
    public function updateTask(array $data, $id):Task;
    public function deleteTask($id);
    public function showTask($id):Task;
    public function searchTask($name):Task;
}
?>