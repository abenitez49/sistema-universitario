<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Uninorte') }}</title>
        <!-- Fonts & Tailwind -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <!-- Fondo idéntico al de tu sistema -->
    <body class="font-sans antialiased text-gray-900 bg-[#e5edf5]">
        
        <!-- Alpine.js controla el estado del Sidebar -->
        <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024" class="flex h-screen overflow-hidden">
            
            <!-- SIDEBAR LATERAL (Importado de navigation) -->
            @include('layouts.navigation')

            <!-- ÁREA PRINCIPAL DERECHA -->
            <div class="flex-1 flex flex-col overflow-hidden">
                
                <!-- HEADER SUPERIOR (Perfil + Hamburguesa Móvil) -->
                <header class="flex items-center justify-end px-6 py-4 z-20">
                    <div class="flex items-center gap-4">
                        
                        <!-- Dropdown de Usuario -->
                        <div class="relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 bg-white rounded-lg shadow-sm hover:bg-slate-50 focus:outline-none transition-colors">
                                        <span>{{ Auth::user()->persona->nombres ?? explode(' ', Auth::user()->name)[0] ?? 'Perfil' }}</span>
                                        <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="px-4 py-2 border-b border-slate-100">
                                        <p class="text-xs text-slate-500">Conectado como</p>
                                        <p class="text-sm font-medium text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                        <p class="text-xs text-blue-600 font-bold mt-1">{{ Auth::user()->getRoleNames()->first() ?? 'Sin Rol' }}</p>
                                    </div>
                                    <x-dropdown-link :href="route('profile.edit')" class="text-sm py-2">Mi Perfil</x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" class="text-sm text-red-600 hover:bg-red-50 py-2" onclick="event.preventDefault(); this.closest('form').submit();">
                                            Cerrar Sesión
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        <!-- HAMBURGUESA MÓVIL (Solo visible en pantallas pequeñas) -->
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg bg-white shadow-sm text-slate-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>

                    </div>
                </header>

                <!-- CONTENIDO DINÁMICO (Vistas Blade) -->
                <main class="flex-1 overflow-y-auto overflow-x-hidden">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>