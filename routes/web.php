<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index'])->name('root');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 優先順位を編集する画面を表示（GET）
Route::get('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');

Route::resource('tasks', TaskController::class);


Route::get('/tasks/top', [TaskController::class, 'top'])->name('tasks.top');


Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');



// ドラッグ＆ドロップで並び替えて保存（POST）
Route::post('/tasks/reorder/save', [TaskController::class, 'updateOrder'])->name('tasks.updateOrder');

require __DIR__.'/auth.php';
