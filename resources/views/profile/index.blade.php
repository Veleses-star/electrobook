<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Мой профиль') }}
            </h2>
            <a href="{{ route('profile.edit') }}" class="text-sm text-kid-secondary dark:text-dark-primary hover:underline">
                ✏️ Редактировать
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- ========== БЛОК АВАТАРА + СТАТИСТИКА ========== -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">
                        <!-- Аватар с рамкой -->
                        <div class="relative group">
                            @if($user->avatar_path)
                                <img src="{{ asset('storage/' . $user->avatar_path) }}" 
                                     class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover shadow-lg transition-transform duration-300 group-hover:scale-105 {{ $user->frame_class }}">
                            @else
                                <div class="w-32 h-32 md:w-40 md:h-40 rounded-full bg-gradient-to-br from-kid-primary to-orange-400 flex items-center justify-center text-5xl text-white shadow-lg transition-transform duration-300 group-hover:scale-105 {{ $user->frame_class }}">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            @if($user->frame_class)
                                <div class="absolute -bottom-2 -right-2 bg-yellow-500 rounded-full p-1.5 shadow-md">
                                    ✨
                                </div>
                            @endif
                        </div>

                        <!-- Информация пользователя и кнопки -->
                        <div class="flex-1 text-center md:text-left">
                            <div class="mb-4">
                                <h3 class="text-2xl md:text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $user->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>

                            <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                                @if($user->can_upload_avatar)
                                    <form action="{{ route('profile.avatar.upload') }}" method="POST" enctype="multipart/form-data" class="inline">
                                        @csrf
                                        <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                            📸 Загрузить аватар
                                            <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                                        </label>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-xl text-sm">
                                        🔒 Купите аватар в магазине
                                    </span>
                                @endif

                                @php $hasFrame = \App\Models\Purchase::where('user_id', Auth::id())->whereHas('item', fn($q) => $q->where('type', 'frame'))->exists(); @endphp
                                @if($hasFrame)
                                    <form action="{{ route('profile.toggleFrame') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium transition
                                            {{ $user->frame_class ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800' : 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-800' }}">
                                            {{ $user->frame_class ? '❌ Отключить рамку' : '✨ Включить золотую рамку' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Статистика в три блока -->
                <div class="bg-gray-50 dark:bg-gray-800/50 p-6 grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="text-center">
                        <p class="text-3xl font-black text-kid-primary dark:text-dark-primary">{{ $totalTests }}</p>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">Пройдено тестов</p>
                    </div>
                    <div class="text-center border-x border-gray-200 dark:border-gray-700">
                        <p class="text-3xl font-black text-kid-primary dark:text-dark-primary">{{ round($averageScore, 1) }}%</p>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">Средний балл</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-black text-yellow-500 dark:text-yellow-400">{{ $totalPoints }}</p>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">Всего баллов</p>
                    </div>
                </div>
            </div>

            <!-- ========== ИСТОРИЯ ТЕСТОВ ========== -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        📋 История тестов
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $testResults->count() }} записей)</span>
                    </h3>
                    @if($testResults->isNotEmpty())
                        <span class="text-xs text-gray-400 dark:text-gray-500">Свежие сверху</span>
                    @endif
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($testResults as $result)
                        <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <div class="flex flex-wrap justify-between items-center gap-2">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 dark:text-gray-100">{{ $result->test->title }}</h4>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <span>📚 {{ $result->test->subject->name }}</span>
                                        <span>📅 {{ $result->completed_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-black {{ $result->percentage >= 80 ? 'text-green-600 dark:text-green-400' : ($result->percentage >= 50 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                        {{ $result->percentage }}%
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $result->score }}/{{ $result->max_score }} баллов
                                    </div>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-3 overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500 
                                    {{ $result->percentage >= 80 ? 'bg-green-500' : ($result->percentage >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                    style="width: {{ $result->percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                            <p class="text-4xl mb-2">📭</p>
                            <p>Вы ещё не прошли ни одного теста</p>
                            <a href="{{ route('home') }}" class="mt-3 inline-block text-kid-secondary dark:text-dark-primary hover:underline">Начать обучение →</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ========== МОИ ПОКУПКИ ========== -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        🛍️ Мои покупки
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">({{ $purchases->count() }} шт.)</span>
                    </h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($purchases as $purchase)
                        <div class="p-5 flex flex-wrap justify-between items-center gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-kid-primary to-orange-400 flex items-center justify-center text-xl shadow-md">
                                    @if($purchase->item->type === 'avatar') 👤
                                    @elseif($purchase->item->type === 'frame') 🖼️
                                    @else 🎨 @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-gray-100">{{ $purchase->item->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $purchase->item->type === 'avatar' ? 'Аватар' : ($purchase->item->type === 'frame' ? 'Рамка' : 'Тема') }}
                                        • куплено {{ $purchase->purchased_at->format('d.m.Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-yellow-500 dark:text-yellow-400 font-bold">{{ $purchase->item->price }} ⭐</p>
                                @if($purchase->item->type === 'theme' && !$user->can_change_theme)
                                    <form action="{{ route('shop.equip', $purchase->item_id) }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-2 py-1 rounded-lg">Применить</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                            <p class="text-4xl mb-2">🛒</p>
                            <p>У вас пока нет покупок</p>
                            <a href="{{ route('shop.index') }}" class="mt-3 inline-block text-kid-secondary dark:text-dark-primary hover:underline">Перейти в магазин →</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>