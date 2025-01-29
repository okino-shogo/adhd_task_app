<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    // 一覧表示
    public function index()
    {
        $tasks = Task::orderBy('priority', 'desc')->get(); 
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function reorder()
    {
        $tasks = Task::all(); 
        return view('tasks.reorder', ['tasks' => $tasks]);
    }
    // 新規作成フォーム
    public function create()
    {
        //
    }

    // 新規タスクの保存
    public function store(Request $request)
    {
        // バリデーション例
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            // 他のフィールドも必要に応じて
        ]);

        // 新規保存
        $task = Task::create([
            'task_id' => uniqid(),  
            'task_name' => $validated['task_name'],
            'importance' => $request->importance,
            'deadline' => $request->deadline,
            'estimated_time' => $request->estimated_time,
        ]);


        
        return redirect()->route('tasks.index');
    }

    // 単一タスク表示
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    // 編集フォーム表示
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // タスク更新
    public function update(Request $request, Task $task)
    {
        // バリデーションなど
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            // ...
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index');
    }

    // タスク削除
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }
    public function top()
    {
        $topTask = Task::orderBy('priority', 'desc')->first();
        // JSON返却してもいいし、そのままビューに渡しても良い
        return response()->json($topTask);
    }

    // 完了ボタン
    public function complete(Task $task)
    {
        $task->update(['status' => '完了']);
        // 次の最優先タスクを返す or リダイレクトなど
        $nextTop = Task::where('status', '!=', '完了')
            ->orderBy('priority', 'desc')
            ->first();
        return response()->json($nextTop);
    }


}

