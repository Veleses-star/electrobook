<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 flex-wrap">
            <a href="{{ route('admin.questions.manage', $question->test_id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">← Назад к вопросам</a>
            <h2 class="font-bold text-xl md:text-2xl text-kid-secondary dark:text-dark-primary">Редактировать вопрос</h2>
        </div>
    </x-slot>

    <div class="py-8 md:py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-4 md:p-8">
                <form action="{{ route('admin.question.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4 md:space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Текст вопроса</label>
                            <textarea name="question_text" rows="3" required class="w-full px-3 py-2 md:px-4 md:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">{{ $question->question_text }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Тип вопроса</label>
                                <select name="question_type" id="question_type" required class="w-full px-3 py-2 md:px-4 md:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition" onchange="toggleAnswerMode()">
                                    <option value="single_choice" {{ $question->question_type == 'single_choice' ? 'selected' : '' }}>Один правильный ответ</option>
                                    <option value="multiple_choice" {{ $question->question_type == 'multiple_choice' ? 'selected' : '' }}>Несколько правильных</option>
                                    <option value="text_input" {{ $question->question_type == 'text_input' ? 'selected' : '' }}>Ввод текста</option>
                                    <option value="matching" {{ $question->question_type == 'matching' ? 'selected' : '' }}>Соответствие</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Баллов за вопрос</label>
                                <input type="number" name="points" value="{{ $question->points }}" min="1" class="w-full px-3 py-2 md:px-4 md:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Варианты ответов</label>
                            <div id="answers-container" class="space-y-2 md:space-y-3">
                                @foreach($question->answers as $idx => $answer)
                                    <div class="flex gap-2 md:gap-3 answer-row">
                                        <input type="checkbox" name="correct_answer[]" value="{{ $idx }}" class="correct-checkbox mt-2 md:mt-3 w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-green-500 focus:ring-green-500 dark:bg-dark-card" {{ $answer->is_correct ? 'checked' : '' }}>
                                        <input type="text" name="answers[]" value="{{ $answer->answer_text }}" placeholder="Вариант {{ $idx+1 }}" class="flex-1 px-2 py-1 md:px-4 md:py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addAnswerField()" class="mt-2 text-sm text-blue-600 dark:text-blue-400">+ Добавить ещё вариант</button>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 md:mt-8 w-full py-3 md:py-4 bg-gradient-to-r from-kid-secondary to-blue-400 dark:from-dark-secondary dark:to-blue-600 text-black dark:text-white font-extrabold text-base md:text-lg rounded-xl shadow-lg hover:shadow-xl transition-all">
                        Обновить вопрос
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let answerCount = {{ $question->answers->count() }};

        function addAnswerField() {
            const container = document.getElementById('answers-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'flex gap-2 md:gap-3 answer-row';
            newDiv.innerHTML = `
                <input type="checkbox" name="correct_answer[]" value="${answerCount}" class="correct-checkbox mt-2 md:mt-3 w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-green-500 focus:ring-green-500 dark:bg-dark-card">
                <input type="text" name="answers[]" placeholder="Новый вариант" class="flex-1 px-2 py-1 md:px-4 md:py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
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