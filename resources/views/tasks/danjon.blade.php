@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>ダンジョン管理画面</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <style>
            /* ここがポイント！ */
            . {
                background-image: url('/public/images/background.jpg');
            }

            /* コンテンツの透明度を調整 */
            .container {
                background: rgba(255, 0, 0, 0); /* 透明度を調整 */
                padding: 20px;
                border-radius: 10px;
            } 

            /* ヘッダー部分 */
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px;
                background-color: #444; 
            }

            .header a, .header button {
                text-decoration: none;
                color: #fff;
                font-weight: bold;
                background-color: #555;
                padding: 8px 15px;
                border-radius: 5px;
                border: none;
                cursor: pointer;
            }
            .header a:hover, .header button:hover {
                background-color: #666;
            }

            /* コンテンツをスクロールできるようにするならflex: 1に */
            .main-content {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
            }

            /* ボスモンスター(最優先タスク)表示部分 */
            .boss-monster-container {
                text-align: center;
                margin-top: 50px;
            }

            .boss-monster {
                font-size: 60px;
                font-weight: bold;
                color: #ff6b6b;
                text-shadow: 1px 1px 2px #000;
            }

            /* 討伐ボタン */
            .complete-btn {
                background-color: #e74c3c;
                border: none;
                padding: 10px 20px;
                color: white;
                font-size: 18px;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 15px;
            }

            .complete-btn:hover {
                background-color: #c0392b;
            }

            /* 全討伐完了メッセージ */
            .all-cleared {
                text-align: center;
                font-size: 40px;
                color: #9b59b6;
                margin-top: 80px;
            }

            /* メッセージ表示 */
            .message {
                text-align: center;
                background-color: #3c3c3c;
                margin: 10px auto;
                padding: 10px;
                border-radius: 5px;
                color: #fff;
                width: 80%;
            }

            /* エラーメッセージ（呪文失敗？） */
            .error-container {
                background: #6b1919;
                border-left: 5px solid #ff4c4c;
                color: #ffd6d6;
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
            }

            /* モンスター召喚フォーム（タスク追加フォーム） */
            .task-form {
                background: #363636;
                padding: 15px;
                border-radius: 5px;
                margin: 30px 0;
                max-width: 500px;
                margin-left: auto;
                margin-right: auto;
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
                border: 1px solid #555;
                border-radius: 5px;
                background-color: #2a2a2a;
                color: #fff;
            }

            .task-form input[type="submit"] {
                background: #e74c3c;
                color: white;
                border: none;
                cursor: pointer;
                margin-top: 15px;
                font-size: 16px;
            }

            .task-form input[type="submit"]:hover {
                background: #c0392b;
            }

        </style>
    </head>

    <body>
        <div class="container">

            <!-- ヘッダー -->
            <div class="header">
                <a href="{{ route('tasks.index') }}">🏰 ダンジョン・クエスト管理</a>
                <div>
                    <button>🔔 呪文書（締め切り）</button>
                    <a href="/tasks/reorder">⚔️ パーティ編成（優先度編集）</a>
                </div>
            </div>

            <!-- メインコンテンツ -->
            <div class="main-content">
                <!-- メッセージ表示 -->
                @if (session('message'))
                    <p class="message">{{ session('message') }}</p>
                @endif

                <!-- ボスモンスター（最優先タスク）表示 -->
                @if ($task)
                    <div class="boss-monster-container">
                        <p class="boss-monster">👾 {{ $task->task_name }} 👾</p>
                        <form method="POST" action="{{ route('tasks.complete', $task->id) }}">
                            @csrf
                            <button type="submit" class="complete-btn">討伐完了！</button>
                        </form>
                    </div>
                @else
                    <p class="all-cleared">🎉 モンスターはいなくなった！ダンジョン制覇おめでとう！</p>
                @endif

                <!-- エラーメッセージ -->
                @if ($errors->any())
                    <div class="error-container">
                        <p><strong>呪文が失敗しました！以下のエラーを確認してください:</strong></p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- モンスター召喚フォーム（タスク追加フォーム） -->
                <div class="task-form">
                    <form action="{{ route('tasks.store') }}" method="post">
                        @csrf
                        <label for="task_name">モンスターの名前</label>
                        <input type="text" name="task_name" id="task_name" required>

                        <label for="deadline">出現時刻（締め切り）</label>
                        <input type="time" name="deadline" id="deadline">

                        <label for="importance">モンスターLv（重要度）</label>
                        <input type="number" name="importance" id="importance" min="1" max="5">

                        <label for="estimated_time">戦闘予想時間</label>
                        <input type="time" name="estimated_time" id="estimated_time">

                        <input type="submit" value="🔥 モンスター召喚">
                    </form>
                </div>
            </div>

        </div>
    </body>
    </html>
@endsection
