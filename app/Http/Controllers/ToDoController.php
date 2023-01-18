<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\Task;
use PhpParser\Node\Stmt\Return_;
use Validator;

class ToDoController extends Controller
{

    public function index()
    {
        return view('todotask.index');
    }


    public function addTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task' => 'required|unique:task',
        ]);

        if ($validator->passes()) {
            $task = new Task();
            $task->task = $request->task;
            $task->c_time = date('d-m-Y');
            $task->status = 0;
            $result = $task->save();
            if ($result) {
                return response()->json(['msg' => 'Task Created Succesfully']);
            } else {
                return response()->json(['error' => 'Error msg']);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }


    public function getallTask(Request $request)
    {
        $task_data = Task::where('status', '!=', 1)->get();
        return response()->json([
            'status' => 200,
            'data' => $task_data
        ]);
    }


    public function deleteTask(Request $request)
    {
        $task_data = Task::find($request->id);
        $result = $task_data->delete();
        if ($result) {
            return response()->json([
                'status' => 'Task Deleted'
            ]);
        } else {
            return response()->json([
                'status' => 'Something Wrong'
            ]);
        }
    }

    public function taskCompleted(Request $request)
    {
        $task_data = Task::find($request->id);
        $task_data->status = 1;
        $result = $task_data->update();
        if ($result) {
            return response()->json([
                'status' => 'Task Completed'
            ]);
        } else {
            return response()->json([
                'status' => 'Something Wrong'
            ]);
        }
    }


    public function showallTask(Request $request)
    {
        $task_data = Task::all();
        return response()->json([
            'status' => 200,
            'data' => $task_data
        ]);
    }
}
