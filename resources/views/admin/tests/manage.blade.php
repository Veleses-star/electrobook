<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">Управление тестами</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-7xl mx-auto px-4">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 p-4 rounded-xl mb-6">{{ session('success') }}</div>
            @endif

            @foreach($subjects as $subject)
                <div class="mb-10">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4 border-b-2 border-kid-secondary inline-block">{{ $subject->name }}</h3>

                    @php
                        $groupedTests = $subject->tests->groupBy(function($test) {
                            return $test->difficulty->name;
                        });
                    @endphp

                    {{-- ДЕСКТОПНАЯ ТАБЛИЦА (видна на md и выше) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-xs">
                                <tr>
                                    <th class="p-3">Класс</th>
                                    <th class="p-3">Название теста</th>
                                    <th class="p-3">Вопросы</th>
                                    <th class="p-3">Бонус</th>
                                    <th class="p-3">Теория</th>
                                    <th class="p-3 text-center">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($groupedTests as $levelName => $tests)
                                    @foreach($tests as $test)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                            <td class="p-3 font-medium text-gray-700 dark:text-gray-300">{{ $levelName }}</td>
                                            <td class="p-3 font-bold text-gray-800 dark:text-gray-100">{{ $test->title }}</td>
                                            <td class="p-3 text-gray-500 dark:text-gray-400">{{ $test->questions->count() }}</td>
                                            <td class="p-3 text-gray-500 dark:text-gray-400">{{ $test->points_for_completion }} ⭐</td>
                                            <td class="p-3">
                                                @if($test->theory)
                                                    <span class="text-green-600 dark:text-green-400">✓</span>
                                                @else
                                                    <span class="text-gray-300 dark:text-gray-600">—</span>
                                                @endif
                                            </td>
                                            <td class="p-3 text-center">
                                                <div class="flex gap-2 justify-center">
                                                    <a href="{{ route('admin.test.edit', $test->id) }}" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded hover:bg-blue-200 transition text-xs">Ред.</a>
                                                    <a href="{{ route('admin.questions.manage', $test->id) }}" class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded hover:bg-orange-200 transition text-xs">Вопросы</a>
                                                    <form action="{{ route('admin.test.delete', $test->id) }}" method="POST" onsubmit="return confirm('Удалить тест и все вопросы?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded hover:bg-red-200 transition text-xs">Удалить</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- Строка-разделитель или кнопка добавления теста для категории — добавим в конце группы --}}
                                    @if($loop->last)
                                        <tr class="bg-gray-50 dark:bg-gray-800/30">
                                            <td colspan="6" class="p-3 text-right">
                                                <a href="{{ route('admin.tests.create', ['subject_id' => $subject->id, 'difficulty_id' => $tests->first()->difficulty_id]) }}" 
                                                   class="inline-block px-4 py-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-md text-sm hover:bg-green-200 transition">
                                                    + Добавить тест в эту категорию
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- МОБИЛЬНЫЕ КАРТОЧКИ (видно только на экранах < md) --}}
                    <div class="md:hidden space-y-4">
                        @foreach($groupedTests as $levelName => $tests)
                            <div class="mb-4">
                                <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
                                    <h4 class="text-xl font-semibold text-gray-700 dark:text-gray-300">{{ $levelName }}</h4>
                                    <a href="{{ route('admin.tests.create', ['subject_id' => $subject->id, 'difficulty_id' => $tests->first()->difficulty_id]) }}" 
                                       class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-sm hover:bg-green-200 transition">
                                        + Добавить тест
                                    </a>
                                </div>
                                @foreach($tests as $test)
                                    <div class="bg-white dark:bg-dark-card rounded-xl shadow p-4 mb-3">
                                        <p class="font-bold text-gray-800 dark:text-gray-100">{{ $test->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Класс: {{ $levelName }}</p>
                                        <div class="flex justify-between items-center mt-2">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Вопросов: {{ $test->questions->count() }} | Бонус: {{ $test->points_for_completion }} ⭐
                                            </div>
                                            @if($test->theory)
                                                <span class="text-xs text-green-600 dark:text-green-400">📖 Теория есть</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-2 mt-3 flex-wrap">
                                            <a href="{{ route('admin.test.edit', $test->id) }}" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded text-sm">Редактировать</a>
                                            <a href="{{ route('admin.questions.manage', $test->id) }}" class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded text-sm">Вопросы</a>
                                            <form action="{{ route('admin.test.delete', $test->id) }}" method="POST" onsubmit="return confirm('Удалить тест?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded text-sm">Удалить</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>