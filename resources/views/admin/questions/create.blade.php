<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 flex-wrap">
            <a href="{{ route('admin.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">← Назад</a>
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">Добавить вопрос к: {{ $test->title }}</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8">
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 p-4 rounded-xl mb-6">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.questions.store', $test->id) }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Текст вопроса</label>
                            <textarea name="question_text" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary outline-none transition" placeholder="Введите вопрос..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Тип вопроса</label>
                                <select name="question_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary outline-none">
                                    <option value="single_choice">Один правильный ответ</option>
                                    <option value="multiple_choice">Несколько правильных</option>
                                    <option value="text_input">Ввод текста</option>
                                    <option value="matching">Соответствие</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Баллов за вопрос</label>
                                <input type="number" name="points" value="1" min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Варианты ответов (отметь правильные)</label>
                            <div class="space-y-3" id="answers-container">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="flex gap-3">
                                        <input type="checkbox" name="correct_answer[]" value="{{ $i }}" class="mt-3 w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-green-500 focus:ring-green-500 dark:bg-dark-card">
                                        <input type="text" name="answers[]" placeholder="Вариант {{ $i + 1 }}" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary outline-none">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mt-8 w-full py-4 bg-gradient-to-r from-orange-400 to-orange-500 dark:from-dark-primary dark:to-orange-600 text-black dark:text-white font-extrabold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        Добавить вопрос
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>