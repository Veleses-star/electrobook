<nav x-data="{ open: false }" class="bg-white dark:bg-dark-card border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Главная') }}
                    </x-nav-link>
                    <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                        {{ __('Магазин') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                            {{ __('Админ-панель') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.tests.manage')" :active="request()->routeIs('admin.tests.manage')">
                            {{ __('Управление тестами') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right side: user dropdown + theme toggle -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-2">
                @if(Auth::user()->can_change_theme)
                    <button id="theme-toggle" class="p-2 rounded-full bg-kid-card dark:bg-dark-card shadow">
                        🌙
                    </button>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-dark-card hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition">
                            @if(Auth::user()->avatar_path)
                                <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" 
                                     class="w-10 h-10 rounded-full object-cover mr-2">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-kid-primary to-orange-400 flex items-center justify-center text-white mr-2">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex flex-col items-start">
                                <span>{{ Auth::user()->name }}</span>
                                @if(Auth::user()->selected_status)
                                    <span class="text-xs text-yellow-500 dark:text-yellow-400">🏆 {{ Auth::user()->selected_status }}</span>
                                @endif
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="url('/profile')">
                            {{ __('Мой профиль') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Редактировать профиль') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Выйти') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-300 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Главная') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                {{ __('Магазин') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">
                    {{ __('Админ-панель') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.tests.manage')" :active="request()->routeIs('admin.tests.manage')">
                    {{ __('Управление тестами') }}
                </x-responsive-nav-link>
            @endif
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                @if(Auth::user()->selected_status)
                    <div class="text-xs text-yellow-500 dark:text-yellow-400 mt-1">🏆 {{ Auth::user()->selected_status }}</div>
                @endif
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="url('/profile')">
                    {{ __('Мой профиль') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Редактировать профиль') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Выйти') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

@if(Auth::user()->can_change_theme)
<script>
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            if (localStorage.theme === 'dark') {
                localStorage.theme = 'light';
                document.documentElement.classList.remove('dark');
                this.innerHTML = '🌙';
            } else {
                localStorage.theme = 'dark';
                document.documentElement.classList.add('dark');
                this.innerHTML = '☀️';
            }
        });
        if (localStorage.theme === 'dark') {
            themeToggle.innerHTML = '☀️';
        }
    }
</script>
@endif