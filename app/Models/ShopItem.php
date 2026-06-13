<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'image', 'price', 'description', 'is_active'];

    /**
     * Связь: Товар куплен МНОГО раз (разными людьми)
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}