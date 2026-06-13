{{-- Наследуем основной макет сайта --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-kid-secondary dark:text-dark-primary leading-tight">
            Привет, {{ Auth::user()->name }}!
        </h2>
    </x-slot>

    <div class="py-12 bg-kid-bg dark:bg-dark-bg min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Блок приветствия и баллов --}}
            <div class="bg-kid-card dark:bg-dark-card overflow-hidden shadow-xl sm:rounded-2xl p-6 mb-8 border-b-4 border-kid-primary dark:border-dark-primary">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">Готов учиться?</h3>
                        <p class="text-gray-500 dark:text-gray-400">Выбери предмет и пройди тест!</p>
                    </div>
                    {{-- Показываем баллы пользователя --}}
                    <div class="flex items-center gap-2 bg-yellow-100 dark:bg-yellow-900 px-4 py-2 rounded-xl">
                        <span class="text-2xl">⭐</span>
                        <span class="font-bold text-yellow-600 dark:text-yellow-300 text-xl">{{ Auth::user()->points }}</span>
                        <span class="text-sm text-yellow-500 dark:text-yellow-400">баллов</span>
                    </div>
                </div>
            </div>

            {{-- Сетка с карточками предметов --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @foreach ($subjects as $subject)
                    <a href="{{ route('tests.selectLevel', $subject->id) }}" class="group block bg-kid-card dark:bg-dark-card overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 sm:rounded-2xl border-2 border-transparent hover:border-kid-secondary dark:hover:border-dark-secondary transform hover:-translate-y-1">

                        {{-- Верхняя цветная полоска --}}
                        <div class="h-2 bg-gradient-to-r from-kid-secondary to-kid-primary dark:from-dark-secondary dark:to-dark-primary"></div>

                        <div class="p-6 text-center">
                            <div class="text-6xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                @if($subject->name == 'Математика') 🧮
                                @elseif($subject->name == 'Русский язык') 📝
                                @else 🌍
                                @endif
                            </div>

                            <h4 class="text-xl font-extrabold text-gray-800 dark:text-gray-100 mb-2">{{ $subject->name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $subject->description }}</p>

                            <span class="inline-block px-6 py-2 bg-gradient-to-r from-orange-400 to-orange-500 text-white font-extrabold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                Начать тест →
                            </span>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>