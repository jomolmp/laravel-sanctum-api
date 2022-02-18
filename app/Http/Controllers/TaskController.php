<?php


namespace App\Http\Controllers;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request):Response
    {
        $request -> validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);
      $data[]=$request->all();
       // return Task::create($request->all());
       $tasks=$this->taskRepository->createTask($data);
       return new Response($tasks);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id):Response
    {
        //return Task::find($id);
        $tasks=$this->taskRepository->showTask($id);
        return new Response($tasks);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id):Response
    {
        $task[]=$request->all();

        //$task->update($request->all());
        $tasks=$this->taskRepository->updateTask($task,$id);
        return new Response($tasks);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id):Response
    {
        $task=$this->taskRepository->deleteTask($id);
        //return Task::destroy($id);
        return new Response($task);
    }
     /**
     * search for task by name
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Task::where('name','like','%'.$name.'%')->get();
    }
}
