<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'due_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_completed' => 'boolean',
            'remarks' => 'required|string|max:100',
            'deleted_at' => 'nullable|date|after_or_equal:today',

        ]);
        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'remarks' => $request->remarks,
            'is_completed' => false,
            'deleted_at' => $request->deleted_at,
        ]);
        if ($task) {
            return response([
                'message' => 'Task created successfully',
                'task' => $task
            ]);
        } else {
            return response([
                'message' => 'Task creation failed, please try again'
            ]);
        }
    }

    public function getTasks(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);
        $user = $request->user_id;
        $tasks = Task::where('user_id', $user)->get();
        if ($tasks) {
            return response([
                'message' => 'Tasks retrieved successfully',
                'tasks' => $tasks
            ]);
        } else {
            return response([
                'message' => 'Tasks not found'
            ]);
        }
    }
    public function getTask(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);
        $task = Task::find($id);
        if ($task && $task->user_id == $request->user_id) {
            return response([
                'task' => $task
            ]);
        } else {
            return response([
                'message' => 'Task not found'
            ]);
        }
    }
    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'due_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_completed' => 'boolean',
            'remarks' => 'required|string|max:100',
            'deleted_at' => 'nullable|date|after_or_equal:today',

        ]);
        $task = Task::find($id);
        if ($task && $task->user_id == $request->user_id) {
            $task->name = $request->name;
            $task->description = $request->description;
            $task->due_date = $request->due_date;
            $task->start_time = $request->start_time;
            $task->end_time = $request->end_time;
            $task->remarks = $request->remarks;
            $task->is_completed = $request->is_completed;
            $task->deleted_at = $request->deleted_at;
            $task->update();
            return response([
                'message' => 'Task updated successfully',
                'task' => $task
            ]);
        } else {
            return response([
                'message' => 'Task not found'
            ]);
        }
    }
    public function deleteTask(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $task = Task::find($id);
        if ($task && $task->user_id == $request->user_id) {
            $task->deleted_at = now();
            $task->update();
            return response([
                'message' => 'Task deleted successfully'
            ]);
        } else {
            return response([
                'message' => 'Task not found'
            ]);
        }
    }
}