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
    <div>
        <a href="{{ route('tasks.index') }}" >戻る</a>
    </div>

    <!-- タスク一覧 -->
    <di>
        <h2>優先順位の編集</h2>

        <ul>
            @foreach ($tasks as $task)
            <li>
                <!-- 優先順位（番号） -->
                <div>{{ $loop->iteration }}</div>

                <!-- タスク名 -->
                <div>
                    {{ $task->task_name }}
                </div>

                <!-- 削除ボタン -->
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">削除</button>
                </form>

                <!-- 順番変更ボタン -->
                <button>順番変える</button>
            </li>
            @endforeach
        </ul>

        <!-- 追加ボタン -->
        <div class="mt-4">
            <button>+</button>
        </div>

        <!-- 更新ボタン -->
        <div>
            <button id="update-order">更新</button>
        </div>
    </div>
</div>

@endsection

</body>
</html>
