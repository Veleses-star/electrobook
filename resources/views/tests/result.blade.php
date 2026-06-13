<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                ← На главную
            </a>
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">Результат теста</h2>
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
        relative overflow-hidden flex items-center justify-center">

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

        <div class="relative z-10 max-w-md w-full px-4">
            <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm p-8 rounded-3xl shadow-xl text-center">
                <div class="text-6xl mb-4">🎉</div>
                <h3 class="text-2xl font-extrabold text-gray-800 dark:text-gray-100 mb-2">Тест пройден!</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">{{ $test->title }}</p>

                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-xl mb-6">
                    <p class="text-4xl font-extrabold text-kid-primary dark:text-dark-primary">{{ $correctCount }} / {{ $totalQuestions }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Правильных ответов ({{ $percentage }}%)</p>
                </div>

                <div class="flex items-center justify-center gap-2 bg-yellow-100 dark:bg-yellow-900 px-4 py-2 rounded-xl mb-6">
                    <span class="text-2xl">⭐</span>
                    <span class="font-bold text-yellow-600 dark:text-yellow-300">+{{ $earnedPoints }} баллов</span>
                </div>

                <div class="flex gap-3 justify-center">
                    <a href="{{ route('home') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        На главную
                    </a>
                    <a href="{{ route('shop.index') }}" class="px-6 py-2 bg-kid-secondary dark:bg-dark-secondary text-white font-bold rounded-xl hover:bg-blue-400 dark:hover:bg-blue-600 transition">
                        В магазин
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>