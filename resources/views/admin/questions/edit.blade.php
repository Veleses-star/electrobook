<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 flex-wrap">
            <a href="{{ route('admin.questions.manage', $question->test_id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">← Назад к вопросам</a>
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">Редактировать вопрос</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8">
                <form action="{{ route('admin.question.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Текст вопроса</label>
                            <textarea name="question_text" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">{{ $question->question_text }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Тип вопроса</label>
                                <select name="question_type" id="question_type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition" onchange="toggleAnswerType()">
                                    <option value="single_choice" {{ $question->question_type == 'single_choice' ? 'selected' : '' }}>Один правильный ответ</option>
                                    <option value="multiple_choice" {{ $question->question_type == 'multiple_choice' ? 'selected' : '' }}>Несколько правильных</option>
                                    <option value="text_input" {{ $question->question_type == 'text_input' ? 'selected' : '' }}>Ввод текста</option>
                                    <option value="matching" {{ $question->question_type == 'matching' ? 'selected' : '' }}>Соответствие</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Баллов за вопрос</label>
                                <input type="number" name="points" value="{{ $question->points }}" min="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                            </div>
                        </div>

                        <!-- Блок для single_choice / multiple_choice (варианты с чекбоксами) -->
                        <div id="answers-block" style="display: {{ in_array($question->question_type, ['single_choice', 'multiple_choice']) ? 'block' : 'none' }};">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Варианты ответов (отметьте правильные)</label>
                            <div id="answers-container" class="space-y-3">
                                @foreach($question->answers as $idx => $answer)
                                    <div class="flex gap-3 answer-row">
                                        <input type="checkbox" name="correct_answer[]" value="{{ $idx }}" class="correct-checkbox mt-3 w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-green-500 focus:ring-green-500 dark:bg-dark-card" {{ $answer->is_correct ? 'checked' : '' }}>
                                        <input type="text" name="answers[]" value="{{ $answer->answer_text }}" placeholder="Вариант {{ $idx+1 }}" class="flex-1 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" onclick="addAnswerField()" class="mt-2 text-sm text-blue-600 dark:text-blue-400">+ Добавить ещё вариант</button>
                        </div>

                        <!-- Блок для текстового ввода (text_input) -->
                        <div id="text-input-block" style="display: {{ $question->question_type == 'text_input' ? 'block' : 'none' }};">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Правильный ответ (точное совпадение)</label>
                            <input type="text" name="correct_text" value="{{ $question->answers->first()?->answer_text ?? '' }}" placeholder="Введите правильный ответ" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                        </div>

                        <!-- Блок для соответствия (matching) -->
                        <div id="matching-block" style="display: {{ $question->question_type == 'matching' ? 'block' : 'none' }};">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Пары для соответствия</label>
                            <div id="matching-container" class="space-y-4">
                                @php $pairs = []; @endphp
                                @foreach($question->answers as $answer)
                                    @php
                                        $parts = explode(' → ', $answer->answer_text);
                                        if (count($parts) == 2) {
                                            $pairs[] = ['left' => $parts[0], 'right' => $parts[1]];
                                        }
                                    @endphp
                                @endforeach
                                @foreach($pairs as $idx => $pair)
                                    <div class="matching-pair border rounded-lg p-3 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <input type="text" name="matching_left[]" value="{{ $pair['left'] }}" placeholder="Левая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                            <input type="text" name="matching_right[]" value="{{ $pair['right'] }}" placeholder="Правая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                        </div>
                                    </div>
                                @endforeach
                                @if(empty($pairs))
                                    <div class="matching-pair border rounded-lg p-3 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <input type="text" name="matching_left[]" placeholder="Левая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                            <input type="text" name="matching_right[]" placeholder="Правая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                        </div>
                                    </div>
                                    <div class="matching-pair border rounded-lg p-3 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <input type="text" name="matching_left[]" placeholder="Левая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                            <input type="text" name="matching_right[]" placeholder="Правая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addMatchingPair()" class="mt-3 text-sm text-blue-600 dark:text-blue-400 hover:underline">+ Добавить ещё пару</button>
                        </div>
                    </div>

                    <button type="submit" class="mt-8 w-full py-4 bg-gradient-to-r from-kid-secondary to-blue-400 dark:from-dark-secondary dark:to-blue-600 text-black dark:text-white font-extrabold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        Обновить вопрос
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let answerCount = {{ $question->answers->count() }};
        let matchingPairCount = {{ $question->question_type == 'matching' ? $question->answers->count() : 2 }};

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

        function addMatchingPair() {
            const container = document.getElementById('matching-container');
            const newDiv = document.createElement('div');
            newDiv.className = 'matching-pair border rounded-lg p-3 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30';
            newDiv.innerHTML = `
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" name="matching_left[]" placeholder="Левая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                    <input type="text" name="matching_right[]" placeholder="Правая часть" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                </div>
            `;
            container.appendChild(newDiv);
            matchingPairCount++;
        }

        function toggleAnswerType() {
            const type = document.getElementById('question_type').value;
            const answersBlock = document.getElementById('answers-block');
            const textInputBlock = document.getElementById('text-input-block');
            const matchingBlock = document.getElementById('matching-block');

            answersBlock.style.display = (type === 'single_choice' || type === 'multiple_choice') ? 'block' : 'none';
            textInputBlock.style.display = (type === 'text_input') ? 'block' : 'none';
            matchingBlock.style.display = (type === 'matching') ? 'block' : 'none';

            if (type === 'single_choice') {
                const checkboxes = document.querySelectorAll('#answers-container .correct-checkbox');
                checkboxes.forEach(cb => {
                    cb.onclick = function() {
                        if (this.checked) {
                            checkboxes.forEach(c => { if (c !== this) c.checked = false; });
                        }
                    };
                });
            } else {
                const checkboxes = document.querySelectorAll('#answers-container .correct-checkbox');
                checkboxes.forEach(cb => cb.onclick = null);
            }
        }

        document.addEventListener('DOMContentLoaded', toggleAnswerType);
    </script>
</x-app-layout>