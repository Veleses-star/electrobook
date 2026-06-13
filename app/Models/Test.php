<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id', 'difficulty_id', 'title', 'description', 
        'time_limit', 'points_for_completion', 'is_active', 'theory'
    ];

    /**
     * Связь: Тест ПРИНАДЛЕЖИТ одному предмету
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Связь: Тест ПРИНАДЛЕЖИТ одному уровню сложности
     */
    public function difficulty()
    {
        return $this->belongsTo(DifficultyLevel::class);
    }

    /**
     * Связь: В тесте МНОГО вопросов
     * Мы указываем orderBy, чтобы вопросы шли по порядку
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order_index');
    }
}