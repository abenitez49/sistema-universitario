<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .bg-panel { background-color: #f4f6f9; }
        .table > :not(caption) > * > * { padding: 1rem 0.5rem; border-bottom-color: #f0f0f0; }
        .table th { color: #6c757d; font-weight: 500; font-size: 0.9rem; }
        .btn-editar { background-color: #e2e8f0; color: #334155; border: none; }
        .btn-editar:hover { background-color: #cbd5e1; color: #0f172a; }
        .btn-nuevo { background-color: #2563eb; border: none; }
        .btn-nuevo:hover { background-color: #1d4ed8; }
    </style>

    <div class="min-vh-100 bg-panel py-5">
        <div class="container-fluid px-4" style="max-width: 1200px;">
            
            <h2 class="fw-bold mb-4" style="color: #0f172a;">Matriculación de Alumnos</h2>

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
                <!-- Buscador añadido para Matriculación de Alumnos -->
                <div class="d-flex flex-column flex-md-row justify-content-end align-items-md-center mb-4 gap-3">
                    <div style="max-width: 350px; width: 100%;">
                        <form action="{{ route('matricular-alumnos.index') }}" method="GET" class="d-flex">
                            <input type="text" 
                                name="buscar" 
                                class="form-control form-control-lg fs-6" 
                                placeholder="Buscar materia por código o nombre..."
                                value="{{ request('buscar') }}">
                            <button class="btn btn-secondary ms-2" type="submit">Buscar</button>
                            @if(request('buscar'))
                                <a href="{{ route('matricular-alumnos.index') }}" class="btn btn-outline-danger ms-1" title="Limpiar">X</a>
                            @endif
                        </form>
                    </div>
                </div>
                

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 15%;">Código</th>
                                    <th scope="col" style="width: 30%;">Materia</th>
                                    <th scope="col" style="width: 40%;">Alumnos Matriculados (Total)</th>
                                    <th scope="col" class="text-end" style="width: 15%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($materias as $materia)
                                    <tr>
                                        <td class="text-secondary fw-medium">{{ $materia->codigo }}</td>
                                        <td class="fw-bold" style="color: #1e293b;">{{ $materia->nombre }}</td>
                                        <td>
                                            @if($materia->alumnos->count() > 0)
                                                <span class="badge bg-success rounded-pill fw-normal px-2 py-1 mb-1">
                                                    {{ $materia->alumnos->count() }} Alumnos
                                                </span>
                                                <div class="d-flex flex-wrap gap-1 mt-1">
                                                    @foreach($materia->alumnos->take(5) as $alum)
                                                        <span class="badge bg-light text-dark border fw-normal">
                                                            {{ $alum->persona->nombres }}
                                                        </span>
                                                    @endforeach
                                                    @if($materia->alumnos->count() > 5)
                                                        <span class="badge bg-light text-secondary border fw-normal">...</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted small fst-italic">Sin alumnos</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-editar btn-sm fw-medium px-3 py-1 rounded-3" 
                                                    data-bs-toggle="modal" data-bs-target="#matricularMateria{{ $materia->id }}">
                                                Gestionar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            No hay materias disponibles.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 border-top pt-4">
                        {{ $materias->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modales de Gestión Dinámica por Materia -->
    @foreach ($materias as $materia)
        <div class="modal fade" id="matricularMateria{{ $materia->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">Alumnos: <span class="text-primary">{{ $materia->nombre }}</span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        
                        <!-- Formulario para MATRICULAR un nuevo alumno -->
                        <form method="POST" action="{{ route('matricular-alumnos.store', $materia) }}" class="mb-4 bg-light p-3 rounded-3 border">
                            @csrf
                            <label class="form-label fw-medium text-secondary">Matricular nuevo alumno</label>
                            <div class="d-flex gap-2">
                                <select class="form-select" name="alumno_id" required>
                                    <option value="" disabled selected>Seleccione un alumno por legajo o nombre...</option>
                                    @foreach($alumnos as $alum)
                                        @if(!$materia->alumnos->contains($alum->id))
                                            <option value="{{ $alum->id }}">
                                                [{{ $alum->legajo }}] - {{ $alum->persona->nombres }} {{ $alum->persona->apellidos }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-nuevo text-white fw-medium px-4">Matricular</button>
                            </div>
                        </form>

                        <!-- Lista de alumnos MATRICULADOS -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-secondary">Lista de Clase Actual</h6>
                            <span class="badge bg-primary rounded-pill">{{ $materia->alumnos->count() }} inscritos</span>
                        </div>
                        
                        @if($materia->alumnos->count() > 0)
                            <div class="list-group" style="max-height: 300px; overflow-y: auto;">
                                @foreach($materia->alumnos as $alumnoMateria)
                                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 shadow-sm mb-2 rounded-3">
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $alumnoMateria->persona->nombres }} {{ $alumnoMateria->persona->apellidos }}</span>
                                            <small class="text-muted">Legajo: {{ $alumnoMateria->legajo }} | CI: {{ $alumnoMateria->persona->documento }}</small>
                                        </div>
                                        
                                        <!-- Botón para dar de baja -->
                                        <form action="{{ route('matricular-alumnos.destroy', ['materia' => $materia->id, 'alumno' => $alumnoMateria->id]) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas dar de baja a este alumno?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-medium">Dar de Baja</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light border text-center text-muted mb-0">
                                Esta materia aún no tiene alumnos matriculados.
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 bg-white">
                        <button type="button" class="btn btn-light fw-medium px-4 w-100" data-bs-dismiss="modal">Cerrar Panel</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>