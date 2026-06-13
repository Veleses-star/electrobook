<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShopItem;

class ShopItemsTableSeeder extends Seeder
{
    /**
     * Заполнение таблицы товаров магазина
     */
    public function run(): void
    {
        $items = [
            // Аватары
            [
                'name' => 'Аватар "Котик"',
                'type' => 'avatar',
                'image' => 'cat_avatar.png',
                'price' => 50,
                'description' => 'Милый котик для твоего профиля',
                'is_active' => true,
            ],
            [
                'name' => 'Аватар "Сова"',
                'type' => 'avatar',
                'image' => 'owl_avatar.png',
                'price' => 75,
                'description' => 'Мудрая сова для умных учеников',
                'is_active' => true,
            ],
            [
                'name' => 'Аватар "Ракета"',
                'type' => 'avatar',
                'image' => 'rocket_avatar.png',
                'price' => 100,
                'description' => 'Лети к новым знаниям!',
                'is_active' => true,
            ],
            
            // Рамки
            [
                'name' => 'Золотая рамка',
                'type' => 'frame',
                'image' => 'gold_frame.png',
                'price' => 150,
                'description' => 'Красивая золотая рамка вокруг аватара',
                'is_active' => true,
            ],
            [
                'name' => 'Радужная рамка',
                'type' => 'frame',
                'image' => 'rainbow_frame.png',
                'price' => 120,
                'description' => 'Яркая рамка со всеми цветами радуги',
                'is_active' => true,
            ],
            
            // Темы
            [
                'name' => 'Тёмная тема',
                'type' => 'theme',
                'image' => 'dark_theme.png',
                'price' => 200,
                'description' => 'Переключает сайт в ночной режим',
                'is_active' => true,
            ],
            [
                'name' => 'Лесная тема',
                'type' => 'theme',
                'image' => 'forest_theme.png',
                'price' => 180,
                'description' => 'Зелёные оттенки природы',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            ShopItem::create($item);
        }
    }
}