<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">Панель Администратора</h2>
            <span class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-full text-sm font-bold">Admin Mode</span>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4">

            {{-- Статистика (адаптивная сетка) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-dark-card p-4 rounded-2xl shadow-md border-l-4 border-blue-500 dark:border-blue-400">
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Учеников</p>
                    <p class="text-2xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['users'] }}</p>
                </div>
                <div class="bg-white dark:bg-dark-card p-4 rounded-2xl shadow-md border-l-4 border-orange-500 dark:border-orange-400">
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Тестов</p>
                    <p class="text-2xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['tests'] }}</p>
                </div>
                <div class="bg-white dark:bg-dark-card p-4 rounded-2xl shadow-md border-l-4 border-green-500 dark:border-green-400">
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Пройдено</p>
                    <p class="text-2xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['results'] }}</p>
                </div>
                <div class="bg-white dark:bg-dark-card p-4 rounded-2xl shadow-md border-l-4 border-yellow-500 dark:border-yellow-400">
                    <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Баллов</p>
                    <p class="text-2xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['total_points'] }}</p>
                </div>
            </div>

            {{-- Кнопки действий (адаптивные) --}}
            <div class="flex flex-col sm:flex-row gap-3 mb-8">
                <a href="{{ route('admin.tests.create') }}" class="flex-1 text-center py-3 px-4 bg-gradient-to-r from-kid-secondary to-blue-400 dark:from-dark-secondary dark:to-blue-600 text-black dark:text-white font-extrabold text-sm md:text-base rounded-xl shadow-md hover:shadow-lg transition">
                    + Создать тест
                </a>
                <a href="{{ route('admin.export') }}" class="flex-1 text-center py-3 px-4 bg-gradient-to-r from-kid-secondary to-blue-400 dark:from-dark-secondary dark:to-blue-600 text-black dark:text-white font-extrabold text-sm md:text-base rounded-xl shadow-md hover:shadow-lg transition">
                    📎 Выгрузить отчет (Excel)
                </a>
            </div>

            {{-- Блок последних прохождений --}}
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">📊 Последние прохождения</h3>
                </div>

                {{-- ДЛЯ ПК (md и выше): полноценная таблица --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="p-3">Ученик</th>
                                <th class="p-3">Тест</th>
                                <th class="p-3">Предмет</th>
                                <th class="p-3">Результат</th>
                                <th class="p-3">Дата</th>
                                <th class="p-3">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentResults as $result)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-200">
                                    <td class="p-3 font-medium text-gray-800 dark:text-gray-200">{{ $result->user->name }}</td>
                                    <td class="p-3 text-gray-600 dark:text-gray-400">{{ $result->test->title }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-lg text-xs font-bold">
                                            {{ $result->test->subject->name }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-bold {{ $result->percentage >= 80 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">
                                            {{ $result->percentage }}%
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-500 dark:text-gray-400 text-sm">
                                        {{ date('d.m.Y H:i', strtotime($result->completed_at)) }}
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ route('admin.questions.create', $result->test_id) }}" 
                                           class="inline-block px-2 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded-md text-[11px] font-semibold hover:bg-orange-200 dark:hover:bg-orange-800 transition whitespace-nowrap">
                                            Добавить вопросы
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-500 dark:text-gray-400">Пока нет результатов</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ДЛЯ ТЕЛЕФОНОВ: карточки --}}
                <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentResults as $result)
                        <div class="p-4 space-y-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-gray-200">{{ $result->user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $result->test->title }}</p>
                                </div>
                                <span class="text-lg font-bold {{ $result->percentage >= 80 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">
                                    {{ $result->percentage }}%
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-lg">
                                    {{ $result->test->subject->name }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ date('d.m.Y H:i', strtotime($result->completed_at)) }}
                                </span>
                            </div>
                            <div>
                                <a href="{{ route('admin.questions.create', $result->test_id) }}" 
                                   class="inline-block px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded-md text-xs font-semibold hover:bg-orange-200 dark:hover:bg-orange-800 transition">
                                    Добавить вопросы
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">Пока нет результатов</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>