<?php
declare(strict_types=1);
namespace App\Repositories;
use Illuminate\Support\Collection;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
class TaskRepository implements TaskRepositoryInterface
{
    public function GetAllTask():Collection
    {
        return Task::all();
    }

    public function CreateTask(array $data):Task
    {
        $task = new Task();
        $task->setAttribute('name', $data['name']);
        $task->setAttribute('description', $data['description']);
        $task->setAttribute('status', $data['status']);
        $task->save();
        return $task;
    }

    public function UpdateTask(array $data,$id):Task
    {
        $task=Task::find($id);
        $task->setAttribute('name', $data['name']);
        $task->setAttribute('description', $data['description']);
        $task->setAttribute('status', $data['status']);
        $task->save();
        return $task;
    }

    public function DeleteTask($id):void
    {
        Task::destroy($id);
    }
    
    public function ShowTaskById($id):Task
    {
       $task=Task::find($id); 
       return $task;
    }  
    
    public function searchTaskByName($name): Task
    {
        $task=Task::find($name);
        return $task;
    }
}
?>