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
        @if($subjectName == 'Математика') 
            bg-gradient-to-br from-blue-600 to-indigo-800 
        @elseif($subjectName == 'Русский язык') 
            bg-gradient-to-br from-emerald-700 to-green-900 
        @else 
            bg-gradient-to-br from-teal-600 to-cyan-800 
        @endif
        relative overflow-hidden">

        {{-- Декоративные элементы фона --}}
        @if($subjectName == 'Математика')
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div class="text-white text-9xl absolute top-20 left-10">+</div>
                <div class="text-white text-9xl absolute bottom-20 right-10">=</div>
                <div class="text-white text-8xl absolute top-40 right-20">−</div>
                <div class="text-white text-9xl absolute bottom-40 left-20">×</div>
                <div class="text-white text-8xl absolute top-1/2 left-1/4">÷</div>
            </div>
        @elseif($subjectName == 'Русский язык')
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div class="text-white text-9xl absolute top-20 left-10">А</div>
                <div class="text-white text-9xl absolute bottom-20 right-10">Я</div>
                <div class="text-white text-8xl absolute top-40 right-20">Б</div>
                <div class="text-white text-9xl absolute bottom-40 left-20">В</div>
                <div class="text-white text-8xl absolute top-1/2 left-1/3">!</div>
            </div>
        @else
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div class="text-white text-9xl absolute top-20 left-10">🌿</div>
                <div class="text-white text-9xl absolute bottom-20 right-10">🐾</div>
                <div class="text-white text-8xl absolute top-40 right-20">🌍</div>
                <div class="text-white text-9xl absolute bottom-40 left-20">🌸</div>
                <div class="text-white text-8xl absolute top-1/2 left-1/4">🌳</div>
            </div>
        @endif

        <div class="relative z-10 max-w-3xl mx-auto px-4">
            <form action="{{ route('tests.submit', $test->id) }}" method="POST">
                @csrf
                
                <div class="space-y-8">
                    @foreach($questions as $index => $question)
                        <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm p-6 rounded-2xl shadow-lg">
                            <p class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                                {{ $index + 1 }}. {{ $question->question_text }}
                            </p>
                            
                            <div class="space-y-3">
                                @foreach($question->answers as $answer)
                                    <label class="flex items-center p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" required
                                               class="w-5 h-5 text-kid-primary dark:text-dark-primary focus:ring-kid-primary dark:focus:ring-dark-primary border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                        <span class="ml-3 text-gray-700 dark:text-gray-200">{{ $answer->answer_text }}</span>
                                    </label>
                                @endforeach
                            </div>
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