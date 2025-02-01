<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Gemini\Laravel\Facades\Gemini; // gemini-laravelパッケージを想定
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class GeminiController extends Controller
{
    public function index()
    {
        // 単純にフォームを表示するだけならこちらでreturn view('index');
        return view('index');
    }

    public function post(Request $request)
    {
        // 1. 新しいタスクをまず DB に保存
        //--------------------------------------
        $task = new Task();
        $task->task_name      = $request->input('task_name');
        $task->deadline       = $request->input('deadline');
        $task->importance     = $request->input('importance');
        $task->estimated_time = $request->input('estimated_time');
        $task->user_id = Auth::id();
        $task->save();

        // 2. すべてのタスクを取得
        //--------------------------------------
        $tasks = Task::all();

        // 2-1. AIに渡すためのタスク情報をまとめる
        //     例として、ID・タスク名・重要度・締切・見積時間を並べる
        $tasksListText = '';
        foreach ($tasks as $t) {
            $tasksListText .= "ID: {$t->id}, タスク名: {$t->task_name}, 重要度: {$t->importance}, " .
                            "締切: {$t->deadline}, 見積時間: {$t->estimated_time}\n";
        }

        // 3. AIに投げるためのプロンプト文字列を作成
        //--------------------------------------
        // （以下は例示的なPromptです。実際は適宜カスタマイズしてください）
        $prompt = <<<PROMPT
        あなたはロードのタスク管理をサポートするAIです。
        以下にタスクのリストを表示します。これを「最も優先すべきタスクが rank=1、次に優先度が高いタスクが rank=2...」という形ですべてのタスクに順位を割り振ってください。
        
        [優先度判断ルール例]
        - 重要度(importance)が高い(5に近い)ほど先にやるべき
        - 期限(deadline)が近いほど優先度が高い
        - かかる時間(estimated_time)が短いタスクはなるべく早く着手したいので順位を上げる
        - タスク名「起きる」が含まれている場合は最優先(rank=1) ...
        ...（ここに自分のルールなどを自由に書く）...
        
        同じくらいの優先度の場合は、より早い時間に終わるタスクから先にするとよい
        
        以下がタスク一覧です:
        $tasksListText
        
        【出力形式】
        JSON形式で、各要素が {"id": タスクのID, "rank": 順位} となるようにしてください。
        rankは1からタスク数までの連番で重複しないようにしてください。
        PROMPT;

        // 4. Gemini AI に投げてランク付き結果を取得
        //--------------------------------------
        // generateContent() や streamGenerateContent() などを使用
        $result = Gemini::geminiPro()->generateContent($prompt);
        $answer = $result->text();  // AIからのレスポンス本文を取得
        dump($answer);
        print("-----------");
        print(gettype($answer));
        print("-----------");
        // 5. 返ってきたJSONをパースしてDB更新
        //--------------------------------------
        // 例: AIからは [{"id":1,"rank":1},{"id":2,"rank":2}, ...] という配列JSON想定
        // $jsonData = json_decode($answer, true);
                // 無駄な記号を削除
        $jsonData = json_decode(preg_replace('/^```json|```$/m', '', trim($answer)), true);

        dump($jsonData);
        if (is_array($jsonData)) {
            foreach ($jsonData as $row) {
                // 'id' と 'rank' のキーが存在するかチェック
                if (isset($row['id']) && isset($row['rank'])) {
                    // DB更新
                    Task::where('id', $row['id'])->update([
                        'priority' => $row['rank']
                    ]);
                }
            }
        } else {
            return view('tasks.reorder');
        }

        // 6. 処理後、画面に戻す
        return redirect()->back()->with('status', 'タスクを追加し、AIにより優先順位を更新しました！');
    }
}
