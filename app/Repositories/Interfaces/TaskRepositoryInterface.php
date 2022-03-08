<?php
namespace App\Repositories\Interfaces;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
interface TaskRepositoryInterface
{
    public function GetAllTask(): Collection;
    public function CreateTask(array $data, User $user):Task;
    public function UpdateTask(array $data, $id):Task;
    public function DeleteTask($id);
    public function ShowTaskById($id):Task;
    public function searchTaskByName($name):Task;
    public function GetUserTask(User $user):Collection;
}
