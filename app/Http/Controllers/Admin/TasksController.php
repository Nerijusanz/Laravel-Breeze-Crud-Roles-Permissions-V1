<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StoreTaskRequest;
use App\Http\Requests\Admin\UpdateTaskRequest;
use App\Models\Task;

class TasksController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('task_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tasks = Task::paginate();

        return view('admin.tasks.index',compact('tasks'));
    }


    public function create()
    {
        abort_if(Gate::denies('task_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tasks.create');
    }


    public function store(StoreTaskRequest $request)
    {
        Task::create($request->validated());

        return redirect()->route('admin.tasks.index');
    }


    public function show(Task $task)
    {
        abort_if(Gate::denies('task_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tasks.show',compact('task'));
    }


    public function edit(Task $task)
    {
        abort_if(Gate::denies('task_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tasks.edit',compact('task'));
    }


    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('admin.tasks.show',$task->id);
    }


    public function destroy(Task $task)
    {
        abort_if(Gate::denies('task_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $task->delete();

        return redirect()->route('admin.tasks.index');
    }
}
