<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/subject/{id}/levels', [TestController::class, 'selectLevel'])->name('tests.selectLevel');
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
    Route::get('/tests/create', [AdminController::class, 'createTest'])->name('tests.create');
    Route::post('/tests/store', [AdminController::class, 'storeTest'])->name('tests.store');
    Route::get('/tests/{testId}/questions', [AdminController::class, 'addQuestion'])->name('questions.create');
    Route::post('/tests/{testId}/questions', [AdminController::class, 'storeQuestion'])->name('questions.store');
    Route::get('/export-csv', [AdminController::class, 'exportCsv'])->name('export');
});

require __DIR__.'/auth.php';