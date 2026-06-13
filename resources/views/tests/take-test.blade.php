<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                ← Назад
            </a>
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">{{ $test->title }}</h2>
        </div>
    </x-slot>

    @php
        $subjectName = $test->subject->name;
    @endphp
    <div class="py-12 min-h-screen 
        @if($subjectName == 'Математика') bg-gradient-to-br from-blue-600 to-indigo-800 
        @elseif($subjectName == 'Русский язык') bg-gradient-to-br from-emerald-700 to-green-900 
        @else bg-gradient-to-br from-teal-600 to-cyan-800 @endif
        relative overflow-hidden">

        <div class="relative z-10 max-w-4xl mx-auto px-4">
            <form action="{{ route('tests.submit', $test->id) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    @foreach($questions as $index => $question)
                        <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm p-6 rounded-2xl shadow-lg">
                            <p class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                                {{ $index + 1 }}. {{ $question->question_text }}
                            </p>

                            @if($question->question_type == 'text_input')
                                <div>
                                    <input type="text" name="answers[{{ $question->id }}]" 
                                           class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition"
                                           placeholder="Введите ответ">
                                </div>

                            @elseif($question->question_type == 'matching')
                                <div class="space-y-4">
                                    @foreach($question->matching_pairs as $pair)
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                            <div class="sm:w-1/2 font-medium text-gray-700 dark:text-gray-300">{{ $pair['left'] }}</div>
                                            <div class="sm:w-1/2">
                                                <select name="answers[{{ $question->id }}][{{ $pair['left'] }}]" required 
                                                        class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card dark:text-white focus:border-kid-secondary dark:focus:border-dark-secondary transition">
                                                    <option value="">-- Выберите соответствие --</option>
                                                    @foreach($question->right_options as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @else
                                <!-- single_choice / multiple_choice -->
                                @php $isMultiple = ($question->question_type == 'multiple_choice'); @endphp
                                <div class="space-y-3">
                                    @foreach($question->answers as $answer)
                                        <label class="flex items-start p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                            @if($isMultiple)
                                                <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $answer->id }}" 
                                                       class="mt-1 w-5 h-5 text-kid-primary dark:text-dark-primary focus:ring-kid-primary dark:focus:ring-dark-primary border-gray-300 dark:border-gray-600 dark:bg-dark-card">
                                            @else
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" 
                                                       class="mt-1 w-5 h-5 text-kid-primary dark:text-dark-primary focus:ring-kid-primary dark:focus:ring-dark-primary border-gray-300 dark:border-gray-600 dark:bg-dark-card">
                                            @endif
                                            <span class="ml-3 text-gray-700 dark:text-gray-200">{{ $answer->answer_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 text-center">
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-kid-primary to-orange-500 dark:from-dark-primary dark:to-orange-600 text-black dark:text-white font-extrabold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                        Завершить тест
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>