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

                    @foreach($groupedTests as $levelName => $tests)
                        <div class="mb-6 ml-4">
                            <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                                <h4 class="text-xl font-semibold text-gray-700 dark:text-gray-300">{{ $levelName }}</h4>
                                <a href="{{ route('admin.tests.create', ['subject_id' => $subject->id, 'difficulty_id' => $tests->first()->difficulty_id]) }}" 
                                   class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-sm hover:bg-green-200 transition">
                                    + Добавить тест
                                </a>
                            </div>
                            <div class="space-y-3">
                                @foreach($tests as $test)
                                    <div class="bg-white dark:bg-dark-card p-4 rounded-xl shadow flex justify-between items-center flex-wrap gap-3">
                                        <div>
                                            <p class="font-bold text-gray-800 dark:text-gray-100">{{ $test->title }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Вопросов: {{ $test->questions->count() }} | Бонус: {{ $test->points_for_completion }} ⭐
                                            </p>
                                            @if($test->theory)
                                                <p class="text-xs text-gray-400">📖 Теория добавлена</p>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.test.edit', $test->id) }}" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded hover:bg-blue-200 transition">Редактировать</a>
                                            <a href="{{ route('admin.questions.manage', $test->id) }}" class="px-3 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded hover:bg-orange-200 transition">Вопросы</a>
                                            <form action="{{ route('admin.test.delete', $test->id) }}" method="POST" onsubmit="return confirm('Удалить тест и все вопросы?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded hover:bg-red-200 transition">Удалить</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>