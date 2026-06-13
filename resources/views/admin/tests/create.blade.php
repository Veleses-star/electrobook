<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 flex-wrap">
            <a href="{{ route('admin.tests.manage') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">← Назад</a>
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">{{ isset($test) ? 'Редактировать тест' : 'Создать новый тест' }}</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8">
                <form action="{{ isset($test) ? route('admin.test.update', $test->id) : route('admin.tests.store') }}" method="POST">
                    @csrf
                    @if(isset($test))
                        @method('PUT')
                    @endif

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Название теста</label>
                            <input type="text" name="title" required value="{{ old('title', $test->title ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Предмет</label>
                                <select name="subject_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ (old('subject_id', isset($test) ? $test->subject_id : ($selectedSubject ?? '')) == $subject->id) ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Уровень сложности</label>
                                <select name="difficulty_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ (old('difficulty_id', isset($test) ? $test->difficulty_id : ($selectedDifficulty ?? '')) == $level->id) ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Бонусные баллы за прохождение</label>
                            <input type="number" name="points_for_completion" value="{{ old('points_for_completion', $test->points_for_completion ?? 10) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Теория (HTML или текст)</label>
                            <textarea name="theory" rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">{{ old('theory', $test->theory ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Можно использовать HTML для форматирования. Будет показана на странице теста.</p>
                        </div>
                    </div>

                    <button type="submit" class="mt-8 w-full py-4 bg-gradient-to-r from-kid-secondary to-blue-400 dark:from-dark-secondary dark:to-blue-600 text-black dark:text-white font-extrabold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        {{ isset($test) ? 'Обновить тест' : 'Создать тест' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>