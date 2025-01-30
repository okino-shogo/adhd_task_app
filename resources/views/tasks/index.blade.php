@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>ホーム</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <style>
            /* 全体のデザイン */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            /* コンテナ */
            .container {
                width: 90%;
                max-width: 800px;
                margin: 30px auto;
                background: #fff;
                padding: 20px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }

            /* ヘッダー部分 */
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
            }

            .header a {
                text-decoration: none;
                color: #fff;
                font-weight: bold;
            }

            /* ボタン */
            .btn {
                display: inline-block;
                background-color: #3498db;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                border: none;
                cursor: pointer;
            }

            .btn:hover {
                background-color: #2980b9;
            }

            /* 最優先タスク */
            .top-task-container {
                text-align: center;
                margin: 20px 0;
            }

            .top-task {
                font-size: 50px;
                font-weight: bold;
                color: #333;
            }

            /* タスク完了ボタン */
            .complete-btn {
                background-color: #e74c3c;
                border: none;
                padding: 10px 20px;
                color: white;
                font-size: 18px;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 10px;
            }


            /* エラーメッセージ */
            .error-container {
                background: #ffcccc;
                border-left: 5px solid red;
                color: #d8000c;
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
            }

            /* タスク追加フォーム */
            .task-form {
                background: #ecf0f1;
                padding: 15px;
                border-radius: 5px;
            }

            .task-form label {
                font-weight: bold;
                display: block;
                margin-top: 10px;
            }

            .task-form input {
                width: 100%;
                padding: 8px;
                margin-top: 5px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .task-form input[type="submit"] {
                background: #e74c3c;
                color: white;
                border: none;
                cursor: pointer;
                margin-top: 15px;
                font-size: 16px;
            }

            /* 追加ボタンのとこ */
            .task-form input[type="submit"]:hover {
                background: #c0392b;
            }
        </style>
    </head>

    <body>

        <div class="container">
            <!-- ヘッダー -->
            <div class="header">
                <a href="{{ route('tasks.index') }}" class="btn"> 優先順位アプリ</a>
                <div>
                    <button class="btn">締め切り</button>
                    <a href="/tasks/reorder" class="btn">優先順位を編集</a>
                </div>
            </div>

            <!-- メッセージ表示 -->
            @if (session('message'))
                <p class="message">{{ session('message') }}</p>
            @endif

            <!-- 最優先タスク表示 -->
            @if ($task)
                <div class="top-task-container">
                    <p class="top-task">{{ $task->task_name }}</p>
                    <form method="POST" action="{{ route('tasks.complete', $task->id) }}">
                        @csrf
                        <button type="submit" class="complete-btn">✔️ 完了</button>
                    </form>
                </div>
            @else
                <p style="text-align: center; font-size: 20px;">🎉 すべてのタスクが完了しました！</p>
            @endif

            <!-- エラーメッセージ -->
            @if ($errors->any())
                <div class="error-container">
                    <p><b>{{ count($errors) }}件のエラーがあります。</b></p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- タスク追加フォーム -->
            <div class="task-form">
                <form action="{{ route('tasks.store') }}" method="post">
                    @csrf
                    <label for="task_name">タスク入力</label>
                    <input type="text" name="task_name" id="task_name" required>

                    <label for="deadline">締め切り</label>
                    <input type="time" name="deadline" id="deadline">

                    <label for="importance">重要度</label>
                    <input type="number" name="importance" id="importance" min="1" max="5">

                    <label for="estimated_time">かかる時間</label>
                    <input type="time" name="estimated_time" id="estimated_time">

                    <input type="submit" value="➕ 追加">
                </form>
            </div>
        </div>

    </body>

    </html>
@endsection
