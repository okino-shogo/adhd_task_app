@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}"
                class="bg-blue-500 text-black px-4 py-2 rounded shadow-md hover:bg-blue-600 transition duration-200">
                ← 戻る
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">📋 優先順位の編集</h2>

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
                        <button class="update-task bg-blue-500 text-white px-4 py-2 rounded ml-2"
                            data-id="{{ $task->id }}">更新</button>
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
                    if (prev) {
                        taskList.insertBefore(taskElement, prev);
                    }
                } else if (direction === 'down') {
                    const next = taskElement.nextElementSibling;
                    if (next) {
                        taskList.insertBefore(next, taskElement);
                    }
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

            document.getElementById('save-order').addEventListener('click', function() {
                let order = [];
                document.querySelectorAll('.task-item').forEach((item, index) => {
                    let taskId = item.getAttribute("data-id");
                    order.push({
                        id: taskId,
                        priority: index + 1
                    });
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
    </script>
@endsection
