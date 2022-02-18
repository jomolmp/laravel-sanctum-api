<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Client\Events\ResponseReceived;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks():Collection
    {
        return Task::all();
    }

    public function createTask(array $data):Task
    {
        $task = new Task();
        $task->setAttribute('name', $data['name']);
        $task->setAttribute('description', $data['description']);
        $task->setAttribute('status', $data['status']);
        $task->save();
        return $task;
    }

    public function updateTask(array $data,$id):Task
    {
        $task=Task::find($id);
        $task->setAttribute('name', $data['name']);
        $task->setAttribute('description', $data['description']);
        $task->setAttribute('status', $data['status']);
        $task->save();
        return ($task);
    }

    public function deleteTask($id):void
    {
        Task::destroy($id);

    }
    
    public function showTask($id):Task
    {
       $task=Task::find($id); 
       return $task;
       
    }
    public function searchTask($name):Task
    {
       $task=Task::find($name); 
       return $task;
       
    }
    
}

?>