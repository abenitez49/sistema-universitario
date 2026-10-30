<!-- Fondo oscuro para móviles cuando el menú está abierto -->
<div x-show="sidebarOpen" x-transition.opacity.duration.300ms @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-900/40 lg:hidden" style="display: none;"></div>

<!-- SIDEBAR ENCOGIBLE -->
<aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0 lg:w-[85px] lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out bg-[#e5edf5] lg:static lg:flex-shrink-0 border-r border-gray-200 shadow-sm overflow-hidden">
    
    <!-- HEADER DEL SIDEBAR (Logo + Botón <<) -->
    <div class="flex items-center justify-between h-20 px-4 mt-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden text-decoration-none group">
            <div class="flex-shrink-0 w-11 h-11 bg-white rounded-full shadow-sm flex items-center justify-center border border-gray-100 transition-transform duration-300 ease-in-out group-hover:scale-110">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="text-xl font-bold text-slate-800 whitespace-nowrap">Uninorte</span>
        </a>
        
        <!-- Botón de Colapsar (Referencia Imagen) -->
        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex flex-shrink-0 p-1.5 rounded-lg bg-gray-200/50 hover:bg-gray-300/80 text-slate-600 transition-colors">
            <svg x-show="sidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
            <svg x-show="!sidebarOpen" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
        </button>
    </div>

    <!-- LISTA DE ENLACES -->
    <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto mt-2 pb-10 custom-scrollbar">
        
        <p x-show="sidebarOpen" x-transition.opacity.duration.300ms class="px-1 mt-2 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Principal</p>
        
        <a href="{{ route('dashboard') }}" :class="sidebarOpen ? '' : 'justify-center'"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('dashboard') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
            <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
        </a>

        @hasanyrole('Administrador|Administrativo')
            <p x-show="sidebarOpen" x-transition.opacity.duration.300ms class="px-1 mt-6 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Académico</p>
            
            <a href="{{ route('alumnos.index') }}" :class="sidebarOpen ? '' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('alumnos.*') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('alumnos.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Alumnos</span>
            </a>

            <a href="{{ route('profesores.index') }}" :class="sidebarOpen ? '' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('profesores.*') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('profesores.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Profesores</span>
            </a>

            <a href="{{ route('materias.index') }}" :class="sidebarOpen ? '' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('materias.*') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('materias.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Materias</span>
            </a>

            <a href="{{ route('asignar-profesores.index') }}" :class="sidebarOpen ? '' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('asignar-profesores.*') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('asignar-profesores.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Docencia</span>
            </a>

            <a href="{{ route('matricular-alumnos.index') }}" :class="sidebarOpen ? '' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('matricular-alumnos.*') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('matricular-alumnos.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Matriculaciones</span>
            </a>
        @endhasanyrole

        @hasanyrole('Administrador|Administrativo|Profesor')
            <p x-show="sidebarOpen" x-transition.opacity.duration.300ms class="px-1 mt-6 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Aula</p>

            <a href="{{ route('asistencias.index') }}" :class="sidebarOpen ? '' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('asistencias.*') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('asistencias.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Asistencia</span>
            </a>
        @endhasanyrole

        @hasanyrole('Administrador|Administrativo')
            <p x-show="sidebarOpen" x-transition.opacity.duration.300ms class="px-1 mt-6 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Configuración</p>

            <a href="{{ route('usuarios.index') }}" :class="sidebarOpen ? '' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-300 ease-in-out group {{ request()->routeIs('usuarios.*') ? 'bg-white shadow-md text-blue-700 font-bold' : 'text-slate-600 hover:bg-white hover:shadow-md hover:text-blue-600' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300 ease-in-out group-hover:scale-125 {{ request()->routeIs('usuarios.*') ? 'text-blue-700' : 'text-slate-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Usuarios y Roles</span>
            </a>
        @endhasanyrole

    </nav>
</aside>