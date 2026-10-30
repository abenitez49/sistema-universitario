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
            
            <h2 class="fw-bold mb-4" style="color: #0f172a;">Gestión de Profesores</h2>

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
                        <button class="btn btn-nuevo text-white fw-medium px-4 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#crearProfesorModal">
                            Nuevo Profesor
                        </button>
                        
                        <div style="max-width: 350px; width: 100%;">
                            <form action="{{ route('profesores.index') }}" method="GET" class="d-flex">
                                <input type="text" 
                                    name="buscar" 
                                    class="form-control form-control-lg fs-6" 
                                    placeholder="Buscar por nombre o documento..."
                                    value="{{ request('buscar') }}">
                                <button class="btn btn-secondary ms-2" type="submit">Buscar</button>
                                @if(request('buscar'))
                                    <a href="{{ route('profesores.index') }}" class="btn btn-outline-danger ms-1" title="Limpiar">X</a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Documento</th>
                                    <th scope="col">Título</th>
                                    <th scope="col">Nombre Completo</th>
                                    <th scope="col" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($profesores as $profesor)
                                    <tr>
                                        <td class="text-secondary">{{ $profesor->persona->documento ?? '' }}</td>
                                        <td>
                                            @if($profesor->titulo)
                                                <span class="badge bg-secondary rounded-pill fw-normal">{{ $profesor->titulo }}</span>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="fw-medium" style="color: #1e293b;">
                                            {{ $profesor->persona->nombres ?? '' }} {{ $profesor->persona->apellidos ?? '' }}
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-editar btn-sm fw-medium px-3 py-1 me-2 rounded-3" 
                                                    data-bs-toggle="modal" data-bs-target="#editarProfesorModal{{ $profesor->id }}">
                                                Editar
                                            </button>
                                            <form action="{{ route('profesores.destroy', $profesor) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este profesor?');">
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
                                            No hay profesores registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 border-top pt-4">
                        {{ $profesores->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="crearProfesorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form method="POST" action="{{ route('profesores.store') }}">
                    @csrf
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">Nuevo Profesor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="documento" value="{{ old('documento') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary">Título</label>
                                <input type="text" class="form-control" name="titulo" value="{{ old('titulo') }}" placeholder="Ej: Ing., Lic.">
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
    @foreach ($profesores as $profesor)
        <div class="modal fade" id="editarProfesorModal{{ $profesor->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <!-- Ajuste de ruta usando la variable $profesore por el plural de resource -->
                    <form method="POST" action="{{ route('profesores.update', $profesor->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold">Editar Profesor</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4 py-4">
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="documento" value="{{ old('documento', $profesor->persona->documento ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Título</label>
                                    <input type="text" class="form-control" name="titulo" value="{{ old('titulo', $profesor->titulo) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary">Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $profesor->persona->fecha_nacimiento ?? '') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nombres" value="{{ old('nombres', $profesor->persona->nombres ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="apellidos" value="{{ old('apellidos', $profesor->persona->apellidos ?? '') }}" required>
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