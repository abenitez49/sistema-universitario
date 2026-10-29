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
                                    <th scope="col">Nombre</th>
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
                                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
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
                        <h5 class="modal-title fw-bold">Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="documento" value="{{ old('documento') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Fecha de Nacimiento <span class="text-danger">*</span></label>
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

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary">Rol <span class="text-danger">*</span></label>
                                <select class="form-select" name="rol" required>
                                    <option value="" disabled selected>Seleccione un rol...</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol->name }}" {{ old('rol') == $rol->name ? 'selected' : '' }}>{{ $rol->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium text-secondary">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required>
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
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Documento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="documento" value="{{ old('documento', $usuario->persona->documento ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Fecha de Nacimiento <span class="text-danger">*</span></label>
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

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $usuario->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium text-secondary">Rol <span class="text-danger">*</span></label>
                                    <select class="form-select" name="rol" required>
                                        @foreach($roles as $rol)
                                            <option value="{{ $rol->name }}" {{ $usuario->hasRole($rol->name) ? 'selected' : '' }}>{{ $rol->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium text-secondary">Nueva Contraseña <span class="text-muted fw-normal">(Dejar en blanco para mantener actual)</span></label>
                                <input type="password" class="form-control" name="password">
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