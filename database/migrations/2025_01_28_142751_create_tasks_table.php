<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // タスクの一意のID
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーID（外部キー）
            $table->string('user_name'); // ユーザー名
            $table->string('task_name'); // タスク名
            $table->text('description')->nullable(); // タスク詳細（任意）
            $table->integer('importance')->nullable(); // 重要度（1～5などのスコア、任意）
            $table->time('deadline')->nullable(); // 締切（日時）
            $table->unsignedInteger('estimated_time')->nullable(); // タスクにかかる時間（分単位、正の数）
            $table->integer('priority')->nullable(); // AI計算後の優先順位
            $table->integer('manual_priority')->nullable(); // 手動で設定した優先順位
            $table->string('status')->default('未完了'); // タスクの状態（未完了/完了など）
            $table->timestamps(); // 作成日時と更新日時（Laravel標準）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
