<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タスクの優先順位を編集</title>
</head>
<body>
@extends('layouts.app')

@section('content')
<div>
    <!-- 戻るボタン -->
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="bg-gray-300 text-black px-4 py-2 rounded">戻る</a>
    </div>

    <!-- タスク一覧 -->
    <div class="bg-gray-200 p-4 rounded">
        <h2 class="text-xl font-bold mb-4">優先順位の編集</h2>

        <ul id="task-list" class="space-y-2">
            @foreach ($tasks as $task)
            <li class="flex items-center bg-gray-300 p-2 rounded">
                <!-- 優先順位（番号） -->
                <div class="w-8 text-center font-bold">{{ $loop->iteration }}</div>

                <!-- タスク名 -->
                <div class="flex-grow p-2 bg-gray-100 rounded text-lg font-semibold">
                    {{ $task->task_name }}
                </div>

                <!-- 削除ボタン -->
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded ml-2">削除</button>
                </form>

                <!-- 順番変更ボタン -->
                <button class="bg-gray-400 text-black px-2 py-1 rounded ml-2 cursor-move">順番変える</button>
            </li>
            @endforeach
        </ul>

        <!-- 追加ボタン -->
        <div class="mt-4">
            <button class="w-full bg-gray-400 text-black py-2 rounded">+</button>
        </div>

        <!-- 更新ボタン -->
        <div class="mt-4 text-center">
            <button id="update-order" class="bg-gray-600 text-white px-4 py-2 rounded">更新</button>
        </div>
    </div>
</div>

<!-- 並び替え機能を実装するためのJavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const list = document.getElementById('task-list');
        let dragging = null;

        // ドラッグ開始
        list.addEventListener('dragstart', (e) => {
            dragging = e.target;
            e.target.style.opacity = 0.5;
        });

        // ドラッグ終了
        list.addEventListener('dragend', (e) => {
            e.target.style.opacity = "";
        });

        // ドラッグした要素を移動
        list.addEventListener('dragover', (e) => {
            e.preventDefault();
            const target = e.target.closest("li");
            if (target && target !== dragging) {
                list.insertBefore(dragging, target.nextSibling);
            }
        });

        // 更新ボタンを押したときの処理
        document.getElementById('update-order').addEventListener('click', function () {
            let order = [];
            document.querySelectorAll('#task-list li').forEach((item, index) => {
                let taskId = item.querySelector("form").getAttribute("action").split('/').pop();
                order.push({ id: taskId, priority: index + 1 });
            });

            fetch("{{ route('tasks.updateOrder') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ order })
            }).then(response => response.json())
            .then(data => alert("優先順位が更新されました！"));
        });
    });
</script>
@endsection

</body>
</html>
