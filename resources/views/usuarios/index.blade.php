<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .bg-panel { background-color: #f4f6f9; }
        .table th { color: #6c757d; font-weight: 500; font-size: 0.9rem; }
        .btn-editar { background-color: #e2e8f0; color: #334155; border: none; }
        .btn-editar:hover { background-color: #cbd5e1; color: #0f172a; }
        .btn-eliminar { background-color: #ef4444; color: white; border: none; }
        .btn-eliminar:hover { background-color: #dc2626; color: white; }
        .btn-nuevo { background-color: #2563eb; border: none; }
        .btn-nuevo:hover { background-color: #1d4ed8; }
    </style>

    <div class="min-vh-100 bg-panel py-5">
        <div class="container-fluid px-4" style="max-width: 1200px;">
            
            <h2 class="fw-bold mb-4" style="color: #0f172a;">Gestión de Usuarios</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                        <button class="btn btn-nuevo text-white fw-medium px-4 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
                            Nuevo Usuario
                        </button>
                        
                        <div style="max-width: 350px; width: 100%;">
                            <input type="text" class="form-control form-control-lg fs-6" placeholder="Buscar por nombre o email...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($usuarios as $usuario)
                                    <tr>
                                        <td class="fw-medium" style="color: #1e293b;">
                                            {{ $usuario->persona->nombres ?? '' }} {{ $usuario->persona->apellidos ?? '' }}
                                        </td>
                                        <td class="text-secondary">{{ $usuario->persona->documento ?? '' }}</td>
                                        <td class="text-secondary">{{ $usuario->email }}</td>
                                        <td>
                                            @foreach($usuario->roles as $rol)
                                                <span class="badge bg-secondary rounded-pill fw-normal">{{ $rol->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-editar btn-sm fw-medium px-3 py-1 me-2 rounded-3" 
                                                    data-bs-toggle="modal" data-bs-target="#editarUsuarioModal{{ $usuario->id }}">
                                                Editar
                                            </button>
                                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar el acceso a este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-eliminar btn-sm fw-medium px-3 py-1 rounded-3">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            No hay usuarios registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 border-top pt-4">
                        {{ $usuarios->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">Nueva Cuenta de Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        
                        <!-- PASO 1: Elegir Rol -->
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <label class="form-label fw-bold text-dark">1. ¿Qué tipo de usuario deseas crear? <span class="text-danger">*</span></label>
                            <select class="form-select" name="rol" id="select_rol" required onchange="cambiarFormularioPorRol()">
                                <option value="" disabled selected>Seleccione un rol...</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->name }}" {{ old('rol') == $rol->name ? 'selected' : '' }}>{{ $rol->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SECCIÓN DINÁMICA: Si es Alumno -->
                        <div class="row mb-3 d-none" id="seccion_alumno">
                            <div class="col-12">
                                <label class="form-label fw-medium text-primary">Seleccionar Alumno (Sin usuario activo) <span class="text-danger">*</span></label>
                                <select class="form-select border-primary" name="persona_id" id="input_alumno" disabled required>
                                    <option value="" disabled selected>Elija un alumno de la lista...</option>
                                    @foreach($alumnos as $alum)
                                        <option value="{{ $alum->persona_id }}">Legajo: {{ $alum->legajo }} - {{ $alum->persona->nombres }} {{ $alum->persona->apellidos }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- SECCIÓN DINÁMICA: Si es Profesor -->
                        <div class="row mb-3 d-none" id="seccion_profesor">
                            <div class="col-12">
                                <label class="form-label fw-medium text-success">Seleccionar Profesor (Sin usuario activo) <span class="text-danger">*</span></label>
                                <select class="form-select border-success" name="persona_id" id="input_profesor" disabled required>
                                    <option value="" disabled selected>Elija un profesor de la lista...</option>
                                    @foreach($profesores as $prof)
                                        <option value="{{ $prof->persona_id }}">CI: {{ $prof->persona->documento }} - {{ $prof->persona->nombres }} {{ $prof->persona->apellidos }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- SECCIÓN DINÁMICA: Si es Admin (Campos manuales) -->
                        <div id="seccion_manual">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control manual-input" name="documento" value="{{ old('documento') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control manual-input" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control manual-input" name="nombres" value="{{ old('nombres') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control manual-input" name="apellidos" value="{{ old('apellidos') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- PASO 3: Credenciales de Acceso (Siempre visibles) -->
                        <hr class="my-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Email de Acceso <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Contraseña Temporal <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 bg-white">
                        <button type="button" class="btn btn-light fw-medium px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-nuevo text-white fw-medium px-4">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modales Editar -->
    @foreach ($usuarios as $usuario)
        <div class="modal fade" id="editarUsuarioModal{{ $usuario->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold">Editar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4 py-4">
                            
                            <!-- Edición libre de datos para corregir errores -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="documento" value="{{ old('documento', $usuario->persona->documento ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $usuario->persona->fecha_nacimiento ?? '') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nombres" value="{{ old('nombres', $usuario->persona->nombres ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="apellidos" value="{{ old('apellidos', $usuario->persona->apellidos ?? '') }}" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $usuario->email) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Rol <span class="text-danger">*</span></label>
                                    <select class="form-select" name="rol" required>
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->name }}" {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>{{ $rol->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Nueva Contraseña</label>
                                    <input type="password" class="form-control" name="password" placeholder="(Opcional)">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-0 pt-0 px-4 pb-4 bg-white">
                            <button type="button" class="btn btn-light fw-medium px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-nuevo text-white fw-medium px-4">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Script para cambiar el formulario dinámicamente al Crear Usuario -->
    <script>
        function cambiarFormularioPorRol() {
            const rol = document.getElementById('select_rol').value;
            
            const seccionAlumno = document.getElementById('seccion_alumno');
            const seccionProfesor = document.getElementById('seccion_profesor');
            const seccionManual = document.getElementById('seccion_manual');
            
            const inputAlumno = document.getElementById('input_alumno');
            const inputProfesor = document.getElementById('input_profesor');
            const inputsManuales = document.querySelectorAll('.manual-input');

            // 1. Ocultar y Deshabilitar Todo por defecto (los disabled no se envían al backend)
            seccionAlumno.classList.add('d-none');
            inputAlumno.disabled = true;

            seccionProfesor.classList.add('d-none');
            inputProfesor.disabled = true;

            seccionManual.classList.add('d-none');
            inputsManuales.forEach(i => i.disabled = true);

            // 2. Mostrar lo correspondiente
            if (rol === 'Alumno') {
                seccionAlumno.classList.remove('d-none');
                inputAlumno.disabled = false;
            } 
            else if (rol === 'Profesor') {
                seccionProfesor.classList.remove('d-none');
                inputProfesor.disabled = false;
            } 
            else {
                // Administrador o Administrativo
                seccionManual.classList.remove('d-none');
                inputsManuales.forEach(i => i.disabled = false);
            }
        }

        // Ejecutar al cargar la página por si hay algún error de validación y mantiene el "old('rol')"
        document.addEventListener('DOMContentLoaded', function () {
            if(document.getElementById('select_rol').value !== "") {
                cambiarFormularioPorRol();
            }
        });
    </script>
</x-app-layout>