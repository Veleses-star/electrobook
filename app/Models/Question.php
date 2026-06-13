<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['test_id', 'question_text', 'question_type', 'points', 'order_index'];

    /**
     * Связь: Вопрос ПРИНАДЛЕЖИТ одному тесту
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Связь: У вопроса МНОГО вариантов ответа
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('order_index');
    }
}