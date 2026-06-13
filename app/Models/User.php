<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Поля, которые можно заполнять массово (защита от атак)
     * Мы добавили сюда поля для геймификации: role, avatar, points и т.д.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',       // 'student' или 'admin'
        'avatar',     // путь к картинке аватара
        'frame',      // путь к рамке профиля
        'theme',      // тема оформления (light, dark, etc.)
        'points',     // текущий баланс баллов
    ];

    /**
     * Поля, которые нужно скрыть при выводе (пароль!)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Связь: У одного пользователя МНОГО результатов тестов
     */
    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    /**
     * Связь: У одного пользователя МНОГО покупок в магазине
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}