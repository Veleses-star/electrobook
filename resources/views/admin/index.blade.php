<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">Панель Администратора</h2>
            <span class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 rounded-full text-sm font-bold">Admin Mode</span>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            {{-- Статистика --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-lg border-l-4 border-blue-500 dark:border-blue-400">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Всего учеников</p>
                    <p class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['users'] }}</p>
                </div>
                <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-lg border-l-4 border-orange-500 dark:border-orange-400">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Активных тестов</p>
                    <p class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['tests'] }}</p>
                </div>
                <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-lg border-l-4 border-green-500 dark:border-green-400">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Пройдено тестов</p>
                    <p class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['results'] }}</p>
                </div>
                <div class="bg-white dark:bg-dark-card p-6 rounded-2xl shadow-lg border-l-4 border-yellow-500 dark:border-yellow-400">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Всего баллов в системе</p>
                    <p class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $stats['total_points'] }} ⭐</p>
                </div>
            </div>

            {{-- Кнопка экспорта --}}
            <div class="mb-8 text-right">
                <a href="{{ route('admin.export') }}" 
                   class="inline-block px-6 py-3 bg-gradient-to-r from-kid-secondary to-blue-400 dark:from-dark-secondary dark:to-blue-600 text-black dark:text-white font-extrabold text-sm md:text-base rounded-xl shadow-md hover:shadow-lg transition">
                    📎 Выгрузить отчет (Excel)
                </a>
            </div>

            {{-- Таблица последних прохождений (без кнопок действий) --}}
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">📊 Последние прохождения</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="p-4">Ученик</th>
                                <th class="p-4">Тест</th>
                                <th class="p-4">Предмет</th>
                                <th class="p-4">Результат</th>
                                <th class="p-4">Дата</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentResults as $result)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-200">
                                    <td class="p-4 font-medium text-gray-800 dark:text-gray-200">{{ $result->user->name }}</td>
                                    <td class="p-4 text-gray-600 dark:text-gray-400">{{ $result->test->title }}</td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 rounded-lg text-xs font-bold">
                                            {{ $result->test->subject->name }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="font-bold {{ $result->percentage >= 80 ? 'text-green-600 dark:text-green-400' : 'text-orange-600 dark:text-orange-400' }}">
                                            {{ $result->percentage }}%
                                        </span>
                                    </td>
                                    <td class="p-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $result->completed_at->format('d.m.Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-500 dark:text-gray-400">Пока нет результатов</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>