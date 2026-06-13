<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Выбор уровня (класса) для предмета
    Route::get('/subject/{id}/levels', [TestController::class, 'selectLevel'])->name('tests.selectLevel');
    
    // Список тестов для выбранного предмета и класса
    Route::get('/subject/{subjectId}/class/{difficultyId}/tests', [TestController::class, 'listTests'])->name('tests.list');
    
    // Старт теста по ID (новый маршрут)
    Route::get('/test/{testId}', [TestController::class, 'startTestById'])->name('tests.start.byId');
    
    // Старые маршруты (можно оставить для совместимости, но лучше не использовать)
    Route::get('/test/{subjectId}/{difficultyId}', [TestController::class, 'startTest'])->name('tests.start');
    Route::post('/test/{testId}/submit', [TestController::class, 'submitTest'])->name('tests.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('/shop/buy/{itemId}', [ShopController::class, 'buy'])->name('shop.buy');
    Route::post('/shop/equip/{itemId}', [ShopController::class, 'equip'])->name('shop.equip');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar-upload', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::post('/profile/toggle-frame', [ProfileController::class, 'toggleFrame'])->name('profile.toggleFrame');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/tests', [AdminController::class, 'manageTests'])->name('tests.manage');
    Route::get('/tests/create', [AdminController::class, 'createTest'])->name('tests.create');
    Route::post('/tests/store', [AdminController::class, 'storeTest'])->name('tests.store');
    Route::get('/test/{id}/edit', [AdminController::class, 'editTest'])->name('test.edit');
    Route::put('/test/{id}', [AdminController::class, 'updateTest'])->name('test.update');
    Route::delete('/test/{id}', [AdminController::class, 'deleteTest'])->name('test.delete');

    Route::get('/test/{testId}/questions/manage', [AdminController::class, 'manageQuestions'])->name('questions.manage');
    Route::post('/test/{testId}/questions/store', [AdminController::class, 'storeQuestion'])->name('questions.store');
    Route::delete('/question/{id}', [AdminController::class, 'deleteQuestion'])->name('question.delete');
    Route::get('/question/{id}/edit', [AdminController::class, 'editQuestion'])->name('question.edit');
    Route::put('/question/{id}', [AdminController::class, 'updateQuestion'])->name('question.update');

    Route::get('/export-csv', [AdminController::class, 'exportCsv'])->name('export');
});

require __DIR__.'/auth.php';