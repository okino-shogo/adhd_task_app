<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        $task = Task::where('status', '未完了')
            ->orderBy('priority', 'asc')
            ->first();
        return view('tasks.index', ['task' => $task]);
    }

    public function show()
    {
        $tasks = Task::all();
        return view('tasks.reorder', ['tasks' => $tasks]);
    }
    // 新規作成フォーム

    public function create()
    {
        return view('memos.create');
    }
    // 新規タスクの保存
    public function store(StorePostRequest $request)
    {
        $task = new Task($request->all());

        $task->user_id = $request->user()->id;
        $task->task_name = $request->task_name;
        $task->importance = $request->importance;
        $task->deadline = $request->deadline;
        $task->estimated_time = $request->estimated_time;

        // インスタンスに値を設定して保存
        $task->save();
        // 登録したらindexに戻る
        return redirect()->route('tasks.index')
            ->with('notice', 'タスクを追加しました');
    }


    public function edit($id)
    {
        $task = Task::find($id);
        return view('tasks.edit', ['task' => $task]);
    }

    // タスク更新
    public function update(UpdatePostRequest $request, string $id)
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
        // タスクのステータスを「完了」に更新
        $task->update(['status' => '完了']);
        $nextTask = Task::where('status', '未完了')
            ->orderBy('priority', 'asc')
            ->first();
        if ($nextTask) {
            return redirect()->route('tasks.index');
        }
        // 次の優先度のタスクを取得
        return redirect()->route('tasks.index')->with('message', 'すべてのタスクが完了しました。');
    }

    public function reorder()
    {
        // タスクを並び替える処理をここに追加
        $tasks = Task::orderBy('priority', 'asc')->get();
        return view('tasks.reorder', compact('tasks'));
    }



    public function updateOrder(Request $request)
    {
        // JSONデータを取得
        $priorities = $request->input('order');

        // 各タスクの `priority` を更新
        foreach ($priorities as $taskData) {
            Task::where('id', $taskData['id'])->update(['priority' => (int) $taskData['priority']]);
        }

        return response()->json(['message' => '優先順位が更新されました']);
    }
}
