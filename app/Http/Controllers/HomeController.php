<?php

namespace App\Http\Controllers;

use App\Models\Subject; // Импортируем модель Предмета
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Отображает главную страницу со списком предметов
     */
    public function index()
    {
        // 1. Получаем все предметы из БД
        $subjects = Subject::all();

        // 2. Передаем данные в представление (view)
        return view('home', [
            'subjects' => $subjects
        ]);
    }
}