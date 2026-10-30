<x-app-layout>
    <!-- Incluimos Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos para la animación Hover -->
    <style>
        .card-hover {
            transition: transform 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
            text-decoration: none !important;
            display: block;
        }
        .card-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1) !important;
            z-index: 10;
        }
    </style>

    <!-- Contenedor principal -->
    <div style="background-color: #e5edf5; min-height: calc(100vh - 56px); padding-top: 2.5rem; padding-bottom: 2.5rem;">
        <div class="container-fluid px-4" style="max-width: 1200px;">
            
            <!-- Mensaje de Bienvenida (Visible para todos) -->
            <div class="mb-4">
                <h3 class="fw-bold text-dark" style="color: #0f172a;">
                    ¡Hola, {{ Auth::user()->persona->nombres ?? explode(' ', Auth::user()->name)[0] }}! 
                </h3>
                <p class="text-secondary">Bienvenido al portal universitario.</p>
            </div>

            <!-- ==========================================
                 VISTA ADMINISTRADOR Y ADMINISTRATIVO
            =========================================== -->
            @hasanyrole('Administrador|Administrativo')
                <h4 class="fw-bold mb-4 mt-5" style="color: #0f172a;">Resumen General</h4>

                <div class="row g-4">
                    <!-- Tarjeta 1: Alumnos -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('alumnos.index') }}" class="card border-0 shadow-sm rounded-4 h-100 p-4 card-hover">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="text-secondary fw-medium" style="font-size: 0.95rem;">Total Alumnos</span>
                                <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 42px; height: 42px; background-color: #e8f0fe; color: #1a73e8;">
                                    <svg class="w-5 h-5" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 2.5rem;">{{ $totales['alumnos'] ?? 0 }}</h2>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 2: Materias -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('materias.index') }}" class="card border-0 shadow-sm rounded-4 h-100 p-4 card-hover">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="text-secondary fw-medium" style="font-size: 0.95rem;">Total Materias</span>
                                <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 42px; height: 42px; background-color: #e6f4ea; color: #1e8e3e;">
                                    <svg class="w-5 h-5" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 2.5rem;">{{ $totales['materias'] ?? 0 }}</h2>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 3: Profesores -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('profesores.index') }}" class="card border-0 shadow-sm rounded-4 h-100 p-4 card-hover">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="text-secondary fw-medium" style="font-size: 0.95rem;">Total Profesores</span>
                                <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 42px; height: 42px; background-color: #fce8e6; color: #d93025;">
                                    <svg class="w-5 h-5" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 2.5rem;">{{ $totales['profesores'] ?? 0 }}</h2>
                            </div>
                        </a>
                    </div>

                    <!-- Tarjeta 4: Usuarios -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <a href="{{ route('usuarios.index') }}" class="card border-0 shadow-sm rounded-4 h-100 p-4 card-hover">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="text-secondary fw-medium" style="font-size: 0.95rem;">Total Usuarios</span>
                                <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 42px; height: 42px; background-color: #fef7e0; color: #f9ab00;">
                                    <svg class="w-5 h-5" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 2.5rem;">{{ $totales['usuarios'] ?? 0 }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            @endhasanyrole

            <!-- ==========================================
                 VISTA PROFESOR
            =========================================== -->
            @role('Profesor')
                <h4 class="fw-bold mb-4 mt-5" style="color: #0f172a;">Mis Accesos</h4>
                <div class="row g-4">
                    <!-- Tarjeta de Acceso a Asistencia -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('asistencias.index') }}" class="card border-0 shadow-sm rounded-4 h-100 p-4 card-hover">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="text-secondary fw-medium" style="font-size: 0.95rem;">Control de Aula</span>
                                <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 42px; height: 42px; background-color: #e8f0fe; color: #1a73e8;">
                                    <svg class="w-5 h-5" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h4 class="fw-bold mb-0 text-dark">Llamar Asistencia</h4>
                            </div>
                        </a>
                    </div>
                </div>
            @endrole

            <!-- ==========================================
                 VISTA ALUMNO
            =========================================== -->
            @role('Alumno')
                <h4 class="fw-bold mb-4 mt-5" style="color: #0f172a;">Mi Portal Académico</h4>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center mt-4" style="background-color: #ffffff;">
                    <div class="py-4">
                        <svg class="mx-auto h-16 w-16 text-secondary mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <h4 class="fw-bold text-dark">Próximamente</h4>
                        <p class="text-secondary mb-0">Aquí podrás ver tus asistencias y entregar las tareas que sean creadas por el docente por materia.</p>
                    </div>
                </div>
            @endrole

        </div>
    </div>
</x-app-layout>