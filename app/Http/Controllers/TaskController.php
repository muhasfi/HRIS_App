<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index()
    {
        $isHR = session('role') === 'HR';

        $query = Task::query();

        if (!$isHR) {
            $query->where('assigned_to', session('employee_id'));
        }

        $tasks = $query->latest()->get();

        return view(
            $isHR
                ? 'admin.task.index'
                : 'employee.task.index',
            compact('tasks')
        );
    }

    public function create()
    {
        $employees = Employee::all();
        return view('admin.task.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => [
            'required',
            'string',
            'max:255',
                Rule ::unique(Task::class, 'title')
            ],
            'description' => 'nullable | string',
            'assigned_to' => 'required',
            'due_date'=> 'required | date',
            'status' => 'required | string'
        ], [
            'title.unique' => 'Tugas Sudah Ada'
        ]);

        Task::create($validate);

        return redirect()->route('tasks.index')->with('Succsess', 'Task create successfully');
    }

    public function edit(Task $task)
    {
        $employees = Employee::all();

        return view('admin.task.edit', compact('task','employees'));

    }

   public function show(Task $task)
    {
        $isHR = session('role') === 'HR';

        if (!$isHR && $task->assigned_to != session('employee_id')) {
            abort(403, 'Unauthorized');
        }

        return view(
            $isHR
                ? 'admin.task.show'
                : 'employee.task.show',
            compact('task')
        );
    }

    public function update(Request $request, Task $task)
    {
        $validate = $request->validate([
            'title' => 'required | string | max:255',
            'description' => 'nullable | string',
            'assigned_to' => 'required',
            'due_date'=> 'required | date',
            'status' => 'required | string'
        ]);

        $task->update($validate);


        return redirect()->route('tasks.index')->with('Succsess', 'Task create successfully');
    }

    public function done(int $id)
    {
        $task = Task::find($id);
        $task->update([
            'status' => 'done'
        ]);
        return redirect()->route('tasks.index')->with('Succsess', 'Task marked as done');
    }

    public function pending(int $id)
    {
        $task = Task::find($id);
        $task->update([
            'status' => 'pending'
        ]);
        return redirect()->route('tasks.index')->with('Succsess', 'Task marked as pending');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('Success', 'Task deleted succesfully');
    }
}
