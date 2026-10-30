<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .bg-panel { background-color: #f4f6f9; }
        .table > :not(caption) > * > * { padding: 1rem 0.5rem; border-bottom-color: #f0f0f0; }
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
            
            <h2 class="fw-bold mb-4" style="color: #0f172a;">Gestión de Alumnos</h2>

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
                        <button class="btn btn-nuevo text-white fw-medium px-4 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#crearAlumnoModal">
                            Nuevo Alumno
                        </button>
                        
                        <div style="max-width: 350px; width: 100%;">
                            <form action="{{ route('alumnos.index') }}" method="GET" class="d-flex">
                                <input type="text" 
                                    name="buscar" 
                                    class="form-control form-control-lg fs-6" 
                                    placeholder="Buscar por nombre o legajo..."
                                    value="{{ request('buscar') }}"> <!-- Mantiene el término buscado en el input -->
                                <button class="btn btn-secondary ms-2" type="submit">Buscar</button>
                                <!-- Botón para limpiar la búsqueda si existe un término -->
                                @if(request('buscar'))
                                    <a href="{{ route('alumnos.index') }}" class="btn btn-outline-danger ms-1" title="Limpiar">X</a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Legajo</th>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alumnos as $alumno)
                                    <tr>
                                        <td class="text-secondary fw-bold">{{ $alumno->legajo }}</td>
                                        <td class="text-secondary">{{ $alumno->persona->documento ?? '' }}</td>
                                        <td class="fw-medium" style="color: #1e293b;">
                                            {{ $alumno->persona->nombres ?? '' }} {{ $alumno->persona->apellidos ?? '' }}
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-editar btn-sm fw-medium px-3 py-1 me-2 rounded-3" 
                                                    data-bs-toggle="modal" data-bs-target="#editarAlumnoModal{{ $alumno->id }}">
                                                Editar
                                            </button>
                                            <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este alumno?');">
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
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            No hay alumnos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 border-top pt-4">
                        {{ $alumnos->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="crearAlumnoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form method="POST" action="{{ route('alumnos.store') }}">
                    @csrf
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">Nuevo Alumno</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary">Legajo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="legajo" value="{{ old('legajo') }}" placeholder="Ej: ALU-101" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="documento" value="{{ old('documento') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary">Nacimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombres" value="{{ old('nombres') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="apellidos" value="{{ old('apellidos') }}" required>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 bg-white">
                        <button type="button" class="btn btn-light fw-medium px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-nuevo text-white fw-medium px-4">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modales Editar -->
    @foreach ($alumnos as $alumno)
        <div class="modal fade" id="editarAlumnoModal{{ $alumno->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <form method="POST" action="{{ route('alumnos.update', $alumno) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold">Editar Alumno</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4 py-4">
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Legajo <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="legajo" value="{{ old('legajo', $alumno->legajo) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="documento" value="{{ old('documento', $alumno->persona->documento ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $alumno->persona->fecha_nacimiento ?? '') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nombres" value="{{ old('nombres', $alumno->persona->nombres ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="apellidos" value="{{ old('apellidos', $alumno->persona->apellidos ?? '') }}" required>
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
</x-app-layout>