@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
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

            .timer {
                font-weight: bold;
                text-align: center;
                line-height: 2;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-pack: center;
                justify-content: center;
                -ms-flex-align: center;
                align-items: center;
            }

            .countdown_timer_area {
                display: flex;
                align-items: center;
            }

            .text {
                line-height: 1;
            }

            .num {
                font-size: 24px;
            }

            .num .hour {
                font-family: 'Oswald', sans-serif;
                font-size: 40px;
                width: 40px;
                display: inline-block;
                padding: 0 .2em;
            }

            .num .min {
                font-family: 'Oswald', sans-serif;
                font-size: 40px;
                width: 40px;
                display: inline-block;
                padding: 0 .2em;
            }

            .num .sec {
                font-family: 'Oswald', sans-serif;
                font-size: 40px;
                width: 40px;
                display: inline-block;
                padding: 0 .2em;
            }

            .num .milisec {
                font-family: 'Oswald', sans-serif;
                font-size: 40px;
                width: 40px;
                display: inline-block;
                padding: 0 .2em;
            }

            /* 最優先タスク */
            .top-task-container {
                text-align: center;
                margin: 100px 0;
            }

            .top-task {
                font-size: 100px;
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
                    <a href="/tasks/reorder" class="btn">優先順位を編集</a>
                </div>
                <div class="timer">
                    <div class="countdown_timer_area">
                        <div class="text">終了<br>まで</div>
                        @if ($task)
                            <span class="hour js_time_reset">{{ explode(':', $task->estimated_time)[0] }}</span>時間
                            <span class="min js_time_reset">{{ explode(':', $task->estimated_time)[1] }}</span>分
                            <span class="sec js_time_reset">00</span>秒
                        @else
                            <span class="hour js_time_reset">00</span>時間
                            <span class="min js_time_reset">00</span>分
                            <span class="sec js_time_reset">00</span>秒
                        @endif
                    </div>
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
                    <div class="task-buttons">
                        <button id="startTimerBtn" class="btn">取り掛かる</button>
                        <form method="POST" action="{{ route('tasks.complete', $task->id) }}">
                            @csrf
                            <button type="submit" class="complete-btn">✔ 完了</button>
                        </form>
                    </div>
                </div>
            @else
                <p style="text-align: center; font-size: 90px;">🎉すべてのタスクが完了しました</p>
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
        <script>
            // Bladeから受け取った estimated_time（"HH:MM"）を使う
            let estimatedTimeString = "{{ $task ? $task->estimated_time : '' }}";
            let timer_id = null;
            let goal = null;

            // 「取り掛かる」ボタンを押したときにタイマーを開始する処理
            document.getElementById("startTimerBtn")?.addEventListener("click", function() {
                if (estimatedTimeString) {
                    // 例: "01:02" → hour=1, minute=2
                    const [hourStr, minStr] = estimatedTimeString.split(':');
                    const hourNum = parseInt(hourStr) || 0;
                    const minNum = parseInt(minStr) || 0;

                    // 現在時刻 + タスクにかかる合計ミリ秒
                    let now = new Date();
                    let totalMs = hourNum * 3600000 + minNum * 60000;
                    goal = new Date(now.getTime() + totalMs);

                    recalc(); // タイマー開始
                }
            });

            function countdown(due) {
                // due(=goal)までの残り時間を計算
                const now = new Date().getTime();
                const rest = due - now; // 残ミリ秒

                // restが負(=タイマー終了)なら0扱い
                const clamped = (rest < 0) ? 0 : rest;

                const sec = Math.floor(clamped / 1000) % 60;
                const min = Math.floor(clamped / 1000 / 60) % 60;
                const hour = Math.floor(clamped / 1000 / 60 / 60);

                return [hour, min, sec];
            }

            function recalc() {
                if (!goal) return; // タスクがなければ何もしない

                const [hour, min, sec] = countdown(goal);

                document.querySelector('.hour').textContent = String(hour).padStart(2, '0');
                document.querySelector('.min').textContent = String(min).padStart(2, '0');
                document.querySelector('.sec').textContent = String(sec).padStart(2, '0');

                // 残りが全て0になったら停止
                if (hour === 0 && min === 0 && sec === 0) {
                    clearTimeout(timer_id);
                    return;
                }

                timer_id = setTimeout(recalc, 1000); // 1秒ごとに更新
            }
        </script>


    </body>

    </html>
@endsection
