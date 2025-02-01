@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-2xl">
        <!-- 戻るボタン -->
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}"
                class="bg-blue-500 text-black px-4 py-2 rounded shadow-md hover:bg-blue-600 transition duration-200">
                ← 戻る
            </a>
        </div>

        <!-- タスク一覧 -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                📋 優先順位の編集
            </h2>

            <ul id="task-list" class="space-y-4">
                @foreach ($tasks as $task)
                    <li class="flex items-center bg-gray-100 p-4 rounded shadow-md task-item" data-id="{{ $task->id }}"
                        data-priority="{{ $task->priority }}" data-status="{{ $task->status }}">
                        <div
                            class="w-10 h-10 flex items-center justify-center bg-gray-300 rounded-full font-bold text-lg priority-value">
                            {{ $task->priority }}
                        </div>

                        <div class="flex-grow ml-4 text-lg font-semibold">
                            <input type="text" value="{{ $task->task_name }}"
                                class="task-name bg-transparent border-b-2">
                            <input type="time" value="{{ $task->deadline }}"
                                class="task-deadline bg-transparent border-b-2">
                            <input type="number" value="{{ $task->importance }}"
                                class="task-importance bg-transparent border-b-2" min="1" max="5">
                            <input type="time" value="{{ $task->estimated_time }}"
                                class="estimated_time bg-transparent border-b-2" min="1">
                            <select class="task-status bg-transparent border-b-2">
                                <option value="未完了" @if ($task->status == '未完了') selected @endif>未完了</option>
                                <option value="完了" @if ($task->status == '完了') selected @endif>完了</option>
                            </select>
                        </div>

                        <!-- 上下移動ボタン -->
                        <div class="flex ml-4">
                            <button
                                class="move-up bg-gray-100 text-black px-2 py-1 rounded shadow-md hover:bg-gray-500 transition duration-200">
                                🔼
                            </button>
                            <button
                                class="move-down bg-gray-400 text-black px-2 py-1 rounded shadow-md hover:bg-gray-500 transition duration-200 ml-2">
                                🔽
                            </button>
                        </div>

                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded ml-2">削除</button>
                        </form>
                    </li>
                @endforeach
            </ul>

            <!-- 優先順位保存ボタン -->
            <div class="mt-6 text-center">
                <button id="save-order"
                    class="bg-green-500 text-black px-6 py-2 rounded shadow-md hover:bg-green-600 transition duration-200">
                    優先順位を更新
                </button>
            </div>
        </div>
    </div>

    <!-- 並び替え機能を実装するためのJavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const taskList = document.getElementById('task-list');

            function moveTask(taskElement, direction) {
                if (direction === 'up') {
                    const prev = taskElement.previousElementSibling;
                    if (prev) taskList.insertBefore(taskElement, prev);
                } else if (direction === 'down') {
                    const next = taskElement.nextElementSibling;
                    if (next) taskList.insertBefore(next, taskElement);
                }
                updatePriorities();
            }

            function updatePriorities() {
                document.querySelectorAll('.task-item').forEach((item, index) => {
                    item.dataset.priority = index + 1;
                    item.querySelector('.priority-value').textContent = index + 1;
                });
            }

            document.querySelectorAll('.move-up').forEach(button => {
                button.addEventListener('click', function() {
                    moveTask(this.closest('.task-item'), 'up');
                });
            });

            document.querySelectorAll('.move-down').forEach(button => {
                button.addEventListener('click', function() {
                    moveTask(this.closest('.task-item'), 'down');
                });
            });

            document.querySelectorAll('.delete-task').forEach(button => {
                button.addEventListener('click', function() {
                    const taskElement = this.closest('.task-item');
                    const taskId = taskElement.getAttribute("data-id");

                    fetch(`{{ route('tasks.destroy', '') }}/${taskId}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            }
                        }).then(response => response.json())
                        .then(data => {
                            alert("🗑 タスクが削除されました！");
                            taskElement.remove();
                            updatePriorities();
                        });
                });
            });

            document.getElementById('save-order').addEventListener('click', function() {
                let order = [];
                document.querySelectorAll('.task-item').forEach((item, index) => {
                    order.push({
                        id: item.getAttribute("data-id"),
                        priority: index + 1
                    });
                });
            document.getElementById('save-order').addEventListener('click', function() {
                let updatedTasks = [];
                document.querySelectorAll('.task-item').forEach((item) => {
                    updatedTasks.push(updateTaskData(item));
                });

                fetch("{{ route('tasks.updateOrder') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            order
                        })
                    }).then(response => response.json())
                    .then(data => alert("✅ 優先順位が更新されました！"));
            });

            updatePriorities();
        });

        });
    </script>
@endsection
