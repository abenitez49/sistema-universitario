<x-app-layout>
    <!-- Incluimos Bootstrap 5 solo para esta vista -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Forzamos un fondo gris muy claro y tipografía similar al de tu imagen -->
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
            
            <h2 class="fw-bold mb-4" style="color: #0f172a;">Gestión de Materias</h2>

            <!-- Alertas -->
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

            <!-- Tarjeta Principal -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- Barra de controles -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                        <button class="btn btn-nuevo text-white fw-medium px-4 py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#crearMateriaModal">
                            Nueva Materia
                        </button>
                        
                        <div style="max-width: 350px; width: 100%;">
                            <input type="text" class="form-control form-control-lg fs-6" placeholder="Buscar por código o nombre...">
                        </div>
                    </div>

                    <!-- Tabla -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 15%;">Código</th>
                                    <th scope="col" style="width: 60%;">Nombre</th>
                                    <th scope="col" class="text-end" style="width: 25%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($materias as $materia)
                                    <tr>
                                        <td class="text-secondary">{{ $materia->codigo }}</td>
                                        <td class="fw-medium" style="color: #1e293b;">{{ $materia->nombre }}</td>
                                        <td class="text-end">
                                            
                                            <!-- Botón Editar (Gris/Celeste claro) -->
                                            <button class="btn btn-editar btn-sm fw-medium px-3 py-1 me-2 rounded-3" 
                                                    data-bs-toggle="modal" data-bs-target="#editarMateriaModal{{ $materia->id }}">
                                                Editar
                                            </button>

                                            <!-- Botón Eliminar (Rojo) -->
                                            <form action="{{ route('materias.destroy', $materia) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta materia?');">
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
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            No hay materias registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación usando Bootstrap 5 -->
                    <div class="mt-4 border-top pt-4">
                        {{ $materias->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="crearMateriaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form method="POST" action="{{ route('materias.store') }}">
                    @csrf
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">Nueva Materia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        <div class="mb-3">
                            <label for="codigo" class="form-label fw-medium text-secondary">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-medium text-secondary">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
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
    @foreach ($materias as $materia)
        <div class="modal fade" id="editarMateriaModal{{ $materia->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <form method="POST" action="{{ route('materias.update', $materia) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold">Editar Materia</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4 py-4">
                            <div class="mb-3">
                                <label for="codigo_{{ $materia->id }}" class="form-label fw-medium text-secondary">Código <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="codigo_{{ $materia->id }}" name="codigo" value="{{ old('codigo', $materia->codigo) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_{{ $materia->id }}" class="form-label fw-medium text-secondary">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre_{{ $materia->id }}" name="nombre" value="{{ old('nombre', $materia->nombre) }}" required>
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