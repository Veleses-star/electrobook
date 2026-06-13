<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('tests.selectLevel', $subject->id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition">
                ← Назад к классам
            </a>
            <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary">
                {{ $subject->name }} – {{ $level->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            @if($tests->isEmpty())
                <div class="bg-white dark:bg-dark-card rounded-2xl shadow-xl p-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400">Пока нет тестов для этого класса. Загляните позже!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($tests as $test)
                        <div class="group block bg-white dark:bg-dark-card p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border-l-8 border-kid-secondary dark:border-dark-secondary">
                            <h3 class="text-xl font-extrabold text-gray-800 dark:text-gray-100">{{ $test->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Вопросов: {{ $test->questions->count() }} | Бонус: +{{ $test->points_for_completion }} ⭐
                            </p>
                            
                            @if($test->theory)
                                <button type="button" onclick="openTheoryModal(`{{ addslashes($test->theory) }}`)" 
                                        class="mt-4 w-full py-2 text-sm font-semibold rounded-lg bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800 transition">
                                    📖 Теория
                                </button>
                            @endif
                            
                            <a href="{{ route('tests.start.byId', $test->id) }}" 
                               class="mt-3 inline-block text-kid-secondary dark:text-dark-primary group-hover:underline">
                                Начать тест →
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Модальное окно для теории --}}
    <div id="theoryModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-dark-card rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">📖 Теория</h3>
                <button onclick="closeTheoryModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                    ✖
                </button>
            </div>
            <div id="theoryContent" class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                <!-- подставляется из JavaScript -->
            </div>
        </div>
    </div>

    <script>
        function openTheoryModal(content) {
            document.getElementById('theoryContent').innerHTML = content;
            document.getElementById('theoryModal').classList.remove('hidden');
            document.getElementById('theoryModal').classList.add('flex');
        }
        function closeTheoryModal() {
            document.getElementById('theoryModal').classList.add('hidden');
            document.getElementById('theoryModal').classList.remove('flex');
        }
        // Закрытие по клику на фон
        document.getElementById('theoryModal').addEventListener('click', function(e) {
            if (e.target === this) closeTheoryModal();
        });
    </script>
</x-app-layout>