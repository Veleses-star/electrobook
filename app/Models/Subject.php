<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Поля, которые можно менять
    protected $fillable = ['name', 'icon', 'description'];

    /**
     * Связь: У одного предмета МНОГО тестов
     */
    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}