<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function show($task)
    {
        $tasks = Task::orderBy('priority', 'desc')->get();
        return view('tasks.index', ['task' => $task]);
    }

    public function index()
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
        
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'importance' => 'required|integer|min:1|max:5',
            'deadline' => 'required|date_format:H:i',
            'estimated_time' => 'required|date_format:H:i'
        ]);
        $task = new Task();

        $task->task_name = $request->task_name;
        $task->importance = $request->importance;
        $task->deadline = $request->deadline;
        $task->estimated_time = $request->estimated_time;

        // インスタンスに値を設定して保存
        $task->save();

        // 登録したらindexに戻る
        return redirect(route('tasks.index'));
    }


    public function edit($id)
    {
        $task = Task::find($id);
        return view('tasks.edit', ['task' => $task]);
    }

    // タスク更新
    public function update(Request $request, $id)
    {
        // ここはidで探して持ってくる以外はstoreと同じ
        $task = Task::find($id);
        // 値の用意
        $task->title = $request->title;
        $task->body = $request->body;
        // 保存
        $task->save();
        // 登録したらindexに戻る
        return redirect(route('tasks.index'));
    }
    // タスク削除
    public function destroy($task)
    {
        $task = Task::find($task);
        $task->delete();
        return redirect(route('tasks.index'));
    }


    // 完了ボタン
    public function complete(Task $task)
    {
        $task->update(['status' => '完了']);
        // 次の最優先タスクを返す or リダイレクトなど
        $nextindex= Task::where('status', '!=', '完了')
            ->orderBy('priority', 'desc')
            ->first();
        return response()->json($nextindex);
    }
}
