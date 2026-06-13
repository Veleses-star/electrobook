<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    public $timestamps = false; // Поле purchased_at уже есть в миграции

    protected $fillable = ['user_id', 'item_id'];

    // преобразуем purchased_at в объект даты
    protected $casts = [
        'purchased_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(ShopItem::class);
    }
}