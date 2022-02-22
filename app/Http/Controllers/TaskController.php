<?php
namespace App\Http\Controllers;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
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
        $tasks=$this->taskRepository->GetAllTask();
        return new Response($tasks);
    }

    public function store(TaskCreateRequest $request):Response
    {
       $tasks=$this->taskRepository->CreateTask($request->all());
       return new Response($tasks->toArray(),201);
    }

    public function show($id):Response
    {
        $tasks=$this->taskRepository->ShowTaskByID($id);
        return new Response($tasks);
    }

    public function update(TaskUpdateRequest $request, $id):Response
    {
        $tasks=$this->taskRepository->UpdateTask($request->all(),$id);
        return new Response($tasks->toArray(),201);
    }

    public function destroy($id)
    {
        $this->taskRepository->DeleteTask($id);    
    }
   
    public function search($name):Response
    {
        $task=$this->taskRepository->SearchTaskByName($name);
        return new Response($task);
    }
}
