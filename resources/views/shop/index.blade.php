<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">🛒 Магазин</h2>
            <div class="flex items-center gap-2 bg-yellow-100 dark:bg-yellow-900 px-4 py-2 rounded-xl">
                <span class="text-2xl">⭐</span>
                <span class="font-bold text-yellow-600 dark:text-yellow-300 text-xl">{{ Auth::user()->points }}</span>
                <span class="text-sm text-yellow-500 dark:text-yellow-400">баллов</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-6xl mx-auto px-4">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-400 text-green-700 dark:text-green-200 p-4 mb-6 rounded-xl">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 dark:border-red-400 text-red-700 dark:text-red-200 p-4 mb-6 rounded-xl">{{ session('error') }}</div>
            @endif

            @php
                $categories = [
                    'avatar' => ['title' => 'Аватары', 'icon' => '👤'],
                    'theme'  => ['title' => 'Темы', 'icon' => '🎨'],
                    'status' => ['title' => 'Статусы', 'icon' => '🏆'],
                ];
                $groupedItems = $items->groupBy('type');
            @endphp

            @foreach($categories as $type => $category)
                @if(isset($groupedItems[$type]) && $groupedItems[$type]->count())
                    <div class="mb-12">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                            {{ $category['icon'] }} {{ $category['title'] }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($groupedItems[$type] as $item)
                                @php
                                    $isPurchased = in_array($item->id, $purchasedItems);
                                    $isEquipped = false;
                                    if ($item->type === 'avatar') {
                                        $isEquipped = (bool) Auth::user()->can_upload_avatar;
                                    } elseif ($item->type === 'theme') {
                                        $isEquipped = (bool) Auth::user()->can_change_theme;
                                    } elseif ($item->type === 'status') {
                                        $isEquipped = (Auth::user()->selected_status === $item->name);
                                    }
                                @endphp
                                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-lg overflow-hidden border-2 border-gray-100 dark:border-gray-700 hover:border-kid-secondary dark:hover:border-dark-secondary transition-all duration-300">
                                    <div class="h-48 bg-gradient-to-br from-kid-primary to-orange-400 dark:from-dark-primary dark:to-orange-600 flex items-center justify-center">
                                        <span class="text-6xl">{{ $item->type === 'avatar' ? '👤' : ($item->type === 'status' ? '🏆' : '🎨') }}</span>
                                    </div>
                                    <div class="p-6">
                                        <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-100 mb-2">{{ $item->name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $item->description }}</p>
                                        <div class="mb-4">
                                            <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-full">
                                                {{ $item->type === 'avatar' ? 'Аватар' : ($item->type === 'status' ? 'Статус' : 'Тема') }}
                                            </span>
                                        </div>
                                        @if($isEquipped)
                                            <button disabled class="w-full py-3 bg-gray-300 dark:bg-gray-600 text-black dark:text-gray-200 font-bold rounded-xl cursor-not-allowed">
                                                ✓ Применено
                                            </button>
                                        @elseif($isPurchased)
                                            <form action="{{ route('shop.equip', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full py-3 bg-kid-secondary dark:bg-dark-secondary text-black dark:text-white font-bold rounded-xl hover:bg-blue-400 dark:hover:bg-blue-500 transition-all">
                                                    Применить
                                                </button>
                                            </form>
                                        @else
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-2xl font-extrabold text-kid-primary dark:text-dark-primary">{{ $item->price }}</span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">баллов</span>
                                            </div>
                                            <form action="{{ route('shop.buy', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full py-3 bg-gradient-to-r from-orange-400 to-orange-500 text-black dark:text-white font-extrabold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all {{ Auth::user()->points < $item->price ? 'opacity-50 cursor-not-allowed' : 'hover:from-orange-500 hover:to-orange-600' }}"
                                                        {{ Auth::user()->points < $item->price ? 'disabled' : '' }}>
                                                    Купить
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-app-layout>