<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 flex-wrap">
            <a href="{{ route('admin.tests.manage') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">← Назад к тестам</a>
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">Вопросы: {{ $test->title }}</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 p-4 rounded-xl mb-6">{{ session('success') }}</div>
            @endif

            <!-- Список существующих вопросов -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8 mb-8">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Существующие вопросы</h3>
                @if($questions->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">Пока нет вопросов. Добавьте первый ниже.</p>
                @else
                    <div class="space-y-6">
                        @foreach($questions as $index => $q)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-800 dark:text-gray-100">{{ $index+1 }}. {{ $q->question_text }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Тип: {{ $q->question_type }} | Баллов: {{ $q->points }}</p>
                                        <div class="mt-2 space-y-1">
                                            @foreach($q->answers as $answer)
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="w-5">{{ $answer->is_correct ? '✅' : '❌' }}</span>
                                                    <span class="text-gray-700 dark:text-gray-300">{{ $answer->answer_text }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <a href="{{ route('admin.question.edit', $q->id) }}" class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded hover:bg-blue-200 transition text-center">Редактировать</a>
                                        <form action="{{ route('admin.question.delete', $q->id) }}" method="POST" onsubmit="return confirm('Удалить вопрос?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full sm:w-auto px-3 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded hover:bg-red-200 transition text-center">Удалить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Форма добавления нового вопроса -->
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4">Добавить новый вопрос</h3>
                <form action="{{ route('admin.questions.store', $test->id) }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Текст вопроса</label>
                            <textarea name="question_text" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Тип вопроса</label>
                                <select name="question_type" id="question_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition" onchange="toggleAnswerMode()">
                                    <option value="single_choice">Один правильный ответ</option>
                                    <option value="multiple_choice">Несколько правильных</option>
                                    <option value="text_input">Ввод текста</option>
                                    <option value="matching">Соответствие</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Баллов за вопрос</label>
                                <input type="number" name="points" value="1" min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Варианты ответов</label>
                            <div id="answers-container" class="space-y-3">
                                @for($i = 0; $i < 4; $i++)
                                    <div class="flex gap-3 answer-row">
                                        <input type="checkbox" name="correct_answer[]" value="{{ $i }}" class="correct-checkbox mt-3 w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-green-500 focus:ring-green-500 dark:bg-dark-card">
                                        <input type="text" name="answers[]" placeholder="Вариант {{ $i+1 }}" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                    </div>
                                @endfor
                            </div>
                            <button type="button" onclick="addAnswerField()" class="mt-2 text-sm text-blue-600 dark:text-blue-400">+ Добавить ещё вариант</button>
                        </div>
                    </div>

                    <button type="submit" class="mt-8 w-full py-4 bg-gradient-to-r from-kid-secondary to-blue-400 dark:from-dark-secondary dark:to-blue-600 text-black dark:text-white font-extrabold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        Добавить вопрос
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let answerCount = 4;

        function addAnswerField() {
            const container = document.getElementById('answers-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'flex gap-3 answer-row';
            newDiv.innerHTML = `
                <input type="checkbox" name="correct_answer[]" value="${answerCount}" class="correct-checkbox mt-3 w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-green-500 focus:ring-green-500 dark:bg-dark-card">
                <input type="text" name="answers[]" placeholder="Новый вариант" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
            `;
            container.appendChild(newDiv);
            answerCount++;
        }

        function toggleAnswerMode() {
            const type = document.getElementById('question_type').value;
            const checkboxes = document.querySelectorAll('.correct-checkbox');
            if (type === 'single_choice') {
                checkboxes.forEach(cb => {
                    cb.onclick = function() {
                        if (this.checked) {
                            checkboxes.forEach(c => { if (c !== this) c.checked = false; });
                        }
                    };
                });
            } else {
                checkboxes.forEach(cb => cb.onclick = null);
            }
        }

        document.addEventListener('DOMContentLoaded', toggleAnswerMode);
    </script>
</x-app-layout>