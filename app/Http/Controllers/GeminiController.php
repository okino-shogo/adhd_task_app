<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Task;


class GeminiController extends Controller
{
    /**
     * 全タスクを取得→Geminiに投げて優先順位を決めてもらい→DB更新
     */
    public function recalcAllTaskOrder(Request $request)
    {
        // 1. 全タスクを取得（例: tasksテーブルに格納されている想定）
        $tasks = Task::all();

        // 2. Gemini用のプロンプト組み立て
        //    各タスクを「ID, タスク名, 重要度, 締め切り, 所要時間」などの必要情報を
        //    箇条書きでまとめます。
        $tasksListText = "";
        foreach ($tasks as $task) {
            $tasksListText .= "- ID: {$task->id}, ".
                            "Name: {$task->task_name}, ".
                            "Deadline: {$task->deadline}, ".
                            "Importance: {$task->importance}, ".
                            "EstimatedTime: {$task->estimated_time}\n";
        }

        // ここで「優先順位づけのルール」を、すべてプロンプト内に記述します
        // （締切が近い、重要度が高い、所要時間が長い方が先に着手すべき など）
        // また、「最も優先すべきタスクを rank=1 とし、以下 rank=2,3... のように出力」
        // という指示もプロンプトで行います。
        $prompt = <<<PROMPT
あなたは一日のタスク管理をサポートするAIです。
以下に全タスクのリストを示します。これを「最も優先すべきタスクが rank=1、次に優先度が高いタスクが rank=2...」という形で、すべてのタスクに順位を割り振ってください。

【考慮すべきルール例】
1. 重要度(importance)が高い（5に近い）ほど先にやるべき
2. 締切(deadline)が近いほど優先度が高い
3. かかる時間(estimated_time)が長いタスクはなるべく早く着手したいので順位を上げる
4. タスク名に「起きる」が含まれていれば最優先(rank=1)
5. タスク名に「寝る」が含まれていれば最後(rankが最も大きい)
6. 同じくらいの優先度で迷ったら、より早い時間に終わるタスクから先にするとよい

以下がタスク一覧です:
$tasksListText

【出力形式】
- JSON配列 で、各要素が {"id": タスクのID, "rank": 順位} となるようにしてください。
- rank は 1 からタスク数までの連番で重複しないようにしてください。
- JSON 以外の余分な説明や文章は不要です。配列のみ出力してください。
PROMPT;

        // 3. Gemini API にリクエスト送信 (以下はイメージ)
        // $response = Gemini::complete([
        //     'prompt' => $prompt,
        //     // 必要に応じて他のパラメータを設定
        // ]);

        // // Gemini からのレスポンスを取得した想定
        // // 例: '[{"id":10,"rank":1},{"id":2,"rank":2}, ... ]'
        // $jsonString = $response->text;

        // Mock（開発・サンプル用にダミーJSONを用意）
        // 実際はGeminiの応答を受け取って下さい
        $jsonString = '[{"id":1,"rank":1},{"id":2,"rank":2},{"id":3,"rank":3}]';

        // JSONをデコード
        $rankingData = json_decode($jsonString, true);

        // 4. DBに更新
        //    たとえば tasksテーブルに「ranking」カラムを用意して、そこに rank を保存するとします
        if (is_array($rankingData)) {
            foreach ($rankingData as $item) {
                $taskId = $item['id'] ?? null;
                $rank   = $item['rank'] ?? null;

                if ($taskId && $rank) {
                    // 対象タスクを探して更新
                    $task = $tasks->firstWhere('id', $taskId);
                    if ($task) {
                        $task->ranking = $rank;  // 1が最優先
                        $task->save();
                    }
                }
            }
        }

        return response()->json([
            'message' => '全タスクの優先順位をGeminiの計算で更新しました。',
            'tasks'   => $tasks  // 確認用に全件返す
        ]);
    }
}
