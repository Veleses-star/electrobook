<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theory extends Model
{
    protected $table = 'theory';
    protected $fillable = ['subject_id', 'difficulty_id', 'content'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function difficulty()
    {
        return $this->belongsTo(DifficultyLevel::class, 'difficulty_id');
    }
}