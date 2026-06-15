<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                    ← Назад к предметам
                </a>
                @php
                    $subjectIcon = match($subject->name) {
                        'Математика' => '🧮',
                        'Русский язык' => '📖',
                        'Окружающий мир' => '🌍',
                        default => '📚'
                    };
                @endphp
                <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary flex items-center gap-2">
                    {{ $subjectIcon }} {{ $subject->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen 
        @if($subject->name == 'Математика') 
            bg-gradient-to-br from-blue-600 to-indigo-800 
        @elseif($subject->name == 'Русский язык') 
            bg-gradient-to-br from-emerald-700 to-green-900 
        @else 
            bg-gradient-to-br from-teal-600 to-cyan-800 
        @endif
        relative overflow-hidden">

        {{-- Декоративные элементы фона --}}
        @if($subject->name == 'Математика')
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div class="text-white text-9xl absolute top-20 left-10">+</div>
                <div class="text-white text-9xl absolute bottom-20 right-10">=</div>
                <div class="text-white text-8xl absolute top-40 right-20">−</div>
                <div class="text-white text-9xl absolute bottom-40 left-20">×</div>
                <div class="text-white text-8xl absolute top-1/2 left-1/4">÷</div>
            </div>
        @elseif($subject->name == 'Русский язык')
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

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="text-xl text-white drop-shadow-lg">Выбери свой класс и проверь знания!</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($levels as $level)
                    @php
                        $colorMap = [
                            'class_1' => 'green',
                            'class_2' => 'blue',
                            'class_3' => 'orange',
                            'class_4' => 'purple',
                        ];
                        $color = $colorMap[$level->code] ?? 'gray';
                        
                        $iconMap = match($subject->name) {
                            'Математика' => [
                                'class_1' => '🔢',
                                'class_2' => '📐',
                                'class_3' => '✖️',
                                'class_4' => '📈',
                            ],
                            'Русский язык' => [
                                'class_1' => '🔤',
                                'class_2' => '📖',
                                'class_3' => '✍️',
                                'class_4' => '📜',
                            ],
                            'Окружающий мир' => [
                                'class_1' => '🌱',
                                'class_2' => '🐾',
                                'class_3' => '🌍',
                                'class_4' => '🌳',
                            ],
                            default => [
                                'class_1' => '🌟',
                                'class_2' => '⭐',
                                'class_3' => '✨',
                                'class_4' => '💫',
                            ]
                        };
                        $icon = $iconMap[$level->code] ?? '🎓';
                        $theory = $level->theory;
                    @endphp
                    <a href="{{ route('tests.list', ['subjectId' => $subject->id, 'difficultyId' => $level->id]) }}" 
                       class="group block bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border-2 border-{{ $color }}-300 hover:border-{{ $color }}-500">
                        <div class="p-6 text-center">
                            <div class="text-6xl mb-4 group-hover:scale-110 transition-transform inline-block">
                                {{ $icon }}
                            </div>
                            <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-100 mb-2">{{ $level->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-center justify-center gap-1">
                                <span>⭐</span> от {{ $level->min_points }} баллов
                            </p>
                            <div class="mt-5 h-1.5 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full w-0 group-hover:w-full transition-all duration-700 rounded-full bg-{{ $color }}-500"></div>
                            </div>
                            
                            @if($theory)
                            <button type="button" onclick="openTheoryModal(`{{ addslashes($theory->content) }}`)" 
                                    class="mt-5 w-full py-2.5 text-sm font-semibold rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 transition shadow-md">
                                📖 Изучить теорию
                            </button>
                            @endif
                            
                            <span class="mt-3 inline-block text-base font-semibold 
                                        @if($color == 'purple') text-purple-700 dark:text-purple-300
                                        @else text-{{ $color }}-600 dark:text-{{ $color }}-400 @endif
                                        hover:underline">
                                Выбрать тест →
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        function openTheoryModal(content) {
            document.getElementById('theoryContent').innerHTML = content;
            const modal = document.getElementById('theoryModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeTheoryModal() {
            const modal = document.getElementById('theoryModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        // Закрытие по клику на фон
        document.getElementById('theoryModal').addEventListener('click', function(e) {
            if (e.target === this) closeTheoryModal();
        });
    </script>
</x-app-layout>