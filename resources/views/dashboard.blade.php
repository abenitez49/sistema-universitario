<x-app-layout>
    <!-- Contenedor principal con el color de fondo exacto -->
    <div style="background-color: #e5edf5; min-height: calc(100vh - 65px); padding-top: 2rem;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h2 class="font-bold text-2xl text-gray-900 mb-6">
                Resumen General
            </h2>

            <!-- Grid que fuerza las tarjetas a alinearse lado a lado -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Tarjeta 1: Alumnos -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between" style="min-height: 140px;">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 font-medium text-sm">Total Alumnos</span>
                        <div class="p-2 rounded-lg" style="background-color: #e0f2fe; color: #2563eb;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-4xl font-bold text-gray-900">{{ $totales['alumnos'] ?? 0 }}</span>
                    </div>
                </div>

                <!-- Tarjeta 2: Materias -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between" style="min-height: 140px;">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 font-medium text-sm">Total Materias</span>
                        <div class="p-2 rounded-lg" style="background-color: #dcfce7; color: #16a34a;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-4xl font-bold text-gray-900">{{ $totales['materias'] ?? 0 }}</span>
                    </div>
                </div>

                <!-- Tarjeta 3: Profesores -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between" style="min-height: 140px;">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 font-medium text-sm">Total Profesores</span>
                        <div class="p-2 rounded-lg" style="background-color: #fee2e2; color: #dc2626;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-4xl font-bold text-gray-900">{{ $totales['profesores'] ?? 0 }}</span>
                    </div>
                </div>

                <!-- Tarjeta 4: Usuarios -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between" style="min-height: 140px;">
                    <div class="flex justify-between items-start">
                        <span class="text-slate-500 font-medium text-sm">Total Usuarios</span>
                        <div class="p-2 rounded-lg" style="background-color: #fef08a; color: #ca8a04;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-4xl font-bold text-gray-900">{{ $totales['usuarios'] ?? 0 }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>