<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DifficultyLevel extends Model
{
    protected $table = 'difficulty_levels';
    protected $fillable = ['name', 'code', 'min_points'];

    // Отношение с теорией (один уровень может иметь одну теорию)
    public function theory()
    {
        return $this->hasOne(Theory::class, 'difficulty_id');
    }
}