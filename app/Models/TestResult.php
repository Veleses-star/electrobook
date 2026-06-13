<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    // Отключаем updated_at, так как результат теста не меняется после сдачи
    public $timestamps = false;

    protected $fillable = ['user_id', 'test_id', 'score', 'max_score', 'percentage', 'time_spent'];
    
    // 👇 ДОБАВЬ ЭТО: преобразуем completed_at в объект даты
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}