@section('content')
    @extends('layouts.app')
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>ホーム</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <style>
            /* 最優先タスクのデザイン */
            .top-task {
                font-size: 80px;
            }
        </style>
    </head>

    <body>


        <div>

            <!-- 左上：優先順位アプリボタン -->
            <div>
                <a href="{{ route('tasks.index') }}">
                    優先順位アプリ
                </a>
            </div>

            <!-- 右上の締め切りボタン -->
            <div>
                <!-- 締め切りボタン-->
                <button>締め切り</button>

                <!-- 優先順位を編集ボタン -->
                <a href = "/tasks/reorder">
                    優先順位を編集
                </a>
            </div>

            <!-- 中央の「最優先タスクを表示」 -->

            <div class="top-task-container">
                <div class="top-task">
                    最優先タスクを表示
                </div>

                <!-- 完了ボタン -->
                <div style="margin: 20px 0;">
                    <button style="padding: 10px 20px;">完了</button>
                </div>


                <!-- 画面下の「タスク入力」「締め切り」「重要度」「かかる時間」「追加」ボタン -->
                <div>
                    <form action="{{ route('tasks.store') }}" method="post">
                        @csrf
                        <div>
                            <label for="task_name">タスク入力</label><br>
                            <input type="text" name="task_name" id="task_name">
                        </div>

                        <div class="input-item">
                            <label for="deadline">締め切り</label><br>
                            <input type="time" name="deadline" id="deadline">
                        </div>

                        <div>
                            <label for="importance">重要度</label><br>
                            <input type="number" name="importance" id="importance" min="1" max="5">
                        </div>

                        <div>
                            <label for="estimated_time">かかる時間</label><br>
                            <input type="time" name="estimated_time_hours" id="estimated_time">
                        </div>

                        <input type="submit" value="追加">
                    </form>
                </div>

    </body>

    </html>
@endsection
