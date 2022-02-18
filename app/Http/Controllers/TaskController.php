<?php


namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
   private TaskRepositoryInterface $taskRepository;

   public function __construct(TaskRepositoryInterface $taskRepository)
   {
       $this->taskRepository = $taskRepository;
   }

    public function index():Response
    {
        $tasks=$this->taskRepository->getAllTasks();
        return new Response($tasks);
    }

    public function store(TaskCreateRequest $request):Response
    {
       
       $tasks =$this->taskRepository->createTask($request->all());
       return new Response($tasks->toArray(), 201);
    }


    public function show($id):Response
    {

        $tasks=$this->taskRepository->showTask($id);
        return new Response($tasks);
    }

    
    public function update(TaskUpdateRequest $request, $id):Response
    {   
        $tasks=$this->taskRepository->updateTask($request->all(),$id);
        return new Response($tasks->toArray(),201);
    }

    
    public function destroy($id)
    {
        
        $task=$this->taskRepository->deleteTask($id);
    }

    public function searchTask($name):Response
    {
        
        $tasks=$this->taskRepository->searchTask($name);
        return new Response($tasks);
    }
}
