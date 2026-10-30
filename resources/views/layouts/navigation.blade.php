<nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Altura reducida a h-12 (48px) o h-14 (56px) -->
        <div class="flex justify-between h-14">
            <div class="flex">
                
                <!-- Logo Compacto -->
                <div class="shrink-0 flex items-center mr-6">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div class="bg-blue-600 p-1.5 rounded text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="font-bold text-lg text-slate-800 tracking-tight hidden lg:block">Uninorte</span>
                    </a>
                </div>

                <!-- Enlaces con texto más pequeño (text-sm) y menos padding -->
                <div class="hidden space-x-1 sm:flex overflow-x-auto items-center text-sm">
                    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        Dashboard
                    </x-nav-link>
                    
                    <div class="h-4 w-px bg-slate-200 mx-1"></div>
                    
                    <x-nav-link :href="route('materias.index')" :active="request()->routeIs('materias.*')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('materias.*') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        Materias
                    </x-nav-link>

                    <x-nav-link :href="route('alumnos.index')" :active="request()->routeIs('alumnos.*')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('alumnos.*') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        Alumnos
                    </x-nav-link>

                    <x-nav-link :href="route('profesores.index')" :active="request()->routeIs('profesores.*')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('profesores.*') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        Profesores
                    </x-nav-link>

                    <div class="h-4 w-px bg-slate-200 mx-1"></div>

                    <x-nav-link :href="route('asignar-profesores.index')" :active="request()->routeIs('asignar-profesores.*')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('asignar-profesores.*') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        AsigProfMaterias
                    </x-nav-link>

                    <x-nav-link :href="route('matricular-alumnos.index')" :active="request()->routeIs('matricular-alumnos.*')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('matricular-alumnos.*') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        AsigAlumMaterias
                    </x-nav-link>

                    <x-nav-link :href="route('asistencias.index')" :active="request()->routeIs('asistencias.*')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('asistencias.*') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        Asistencia
                    </x-nav-link>
                    
                    <div class="h-4 w-px bg-slate-200 mx-1"></div>
                    
                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')" class="px-2.5 py-1.5 rounded hover:bg-slate-50 border-none {{ request()->routeIs('usuarios.*') ? 'bg-slate-100 text-blue-700 font-semibold' : 'text-slate-600' }}">
                        Usuarios
                    </x-nav-link>
                </div>
            </div>

            <!-- Menú de Usuario (Settings Dropdown) -->
            <div class="hidden sm:flex sm:items-center sm:ms-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-slate-200 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition shadow-sm">
                            <div class="flex items-center gap-2">
                                <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
                            </div>
                            <div class="ms-2 text-slate-400">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs text-slate-500">Conectado como</p>
                            <p class="text-sm font-medium text-slate-900 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="text-sm">Mi Perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="text-sm text-red-600 hover:bg-red-50" onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar Sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center xl:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú Móvil -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden xl:hidden bg-slate-50 border-b border-slate-200">
        <!-- Opciones del menú móvil... -->
        <div class="pt-2 pb-3 space-y-1 px-4 text-sm">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('materias.index')" :active="request()->routeIs('materias.*')">Materias</x-responsive-nav-link>
            <!-- ... demás enlaces móviles ... -->
        </div>
    </div>
</nav>