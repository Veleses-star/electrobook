<?php

namespace App\Http\Controllers;

use App\Models\ShopItem;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $items = ShopItem::where('is_active', true)->get();
        $purchasedItems = Purchase::where('user_id', Auth::id())->pluck('item_id')->toArray();
        return view('shop.index', compact('items', 'purchasedItems'));
    }

    public function buy(Request $request, $itemId)
    {
        $item = ShopItem::findOrFail($itemId);
        $user = Auth::user();

        if ($user->points < $item->price) {
            return redirect()->back()->with('error', 'Недостаточно баллов!');
        }
        $alreadyPurchased = Purchase::where('user_id', $user->id)
                                    ->where('item_id', $item->id)
                                    ->exists();
        if ($alreadyPurchased) {
            return redirect()->back()->with('error', 'Вы уже купили этот товар!');
        }
        $user->decrement('points', $item->price);
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        return redirect()->back()->with('success', "Товар '{$item->name}' успешно куплен! Нажмите «Применить» для активации.");
    }

    public function equip(Request $request, $itemId)
    {
        $item = ShopItem::findOrFail($itemId);
        $user = Auth::user();
        $purchase = Purchase::where('user_id', $user->id)->where('item_id', $item->id)->first();
        if (!$purchase) {
            return redirect()->back()->with('error', 'Сначала купите товар!');
        }

        if ($item->type === 'avatar') {
            $user->can_upload_avatar = true;
            $message = "Право загружать аватар активировано!";
        } elseif ($item->type === 'theme') {
            $user->can_change_theme = true;
            $message = "Тёмная тема активирована!";
        } elseif ($item->type === 'status') {
            $user->active_status_id = $item->id;
            $message = "Статус '{$item->name}' применён!";
        } else {
            return redirect()->back()->with('error', 'Неизвестный тип товара.');
        }
        $user->save();

        return redirect()->back()->with('success', $message);
    }
}