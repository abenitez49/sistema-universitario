<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .bg-panel { background-color: #f4f6f9; }
        .table th { color: #6c757d; font-weight: 500; font-size: 0.85rem; }
        .btn-nuevo { background-color: #2563eb; border: none; }
        .btn-nuevo:hover { background-color: #1d4ed8; }
        .select-asistencia { font-size: 0.85rem; font-weight: 600; cursor: pointer; }
        .select-presente { color: #198754; background-color: #e8f5e9; border-color: #c8e6c9;}
        .select-ausente { color: #dc3545; background-color: #ffebee; border-color: #ffcdd2;}
        .select-justificado { color: #fd7e14; background-color: #fff3e0; border-color: #ffe0b2;}
    </style>

    <div class="min-vh-100 bg-panel py-5">
        <div class="container-fluid px-4" style="max-width: 1200px;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold m-0" style="color: #0f172a;">Asistencia Diaria: <span class="text-primary">{{ $materia->nombre }}</span></h2>
                <a href="{{ route('asistencias.index') }}" class="btn btn-light border fw-medium px-4">Volver</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    <form method="GET" action="{{ route('asistencias.show', $materia) }}" class="mb-4 bg-light p-3 rounded-3 border d-flex align-items-end gap-3" style="max-width: 400px;">
                        <div class="flex-grow-1">
                            <label class="form-label fw-medium text-secondary">Fecha de Clase</label>
                            <input type="date" class="form-control" name="fecha" value="{{ $fecha }}" required>
                        </div>
                        <button type="submit" class="btn btn-secondary fw-medium px-4">Cargar</button>
                    </form>

                    <form method="POST" action="{{ route('asistencias.store', $materia) }}">
                        @csrf
                        <input type="hidden" name="fecha" value="{{ $fecha }}">
                        
                        <div class="table-responsive mb-4">
                            <table class="table table-hover align-middle mb-0 border">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 15%;">Legajo</th>
                                        <th scope="col" style="width: 35%;">Nombre del Alumno</th>
                                        <th scope="col" class="text-center" style="width: 10%;">1ra Hora</th>
                                        <th scope="col" class="text-center" style="width: 10%;">2da Hora</th>
                                        <th scope="col" class="text-center" style="width: 10%;">3ra Hora</th>
                                        <th scope="col" class="text-center" style="width: 10%;">4ta Hora</th>
                                        <th scope="col" class="text-center" style="width: 10%;">% Diario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($alumnos as $alumno)
                                        @php
                                            $asistencia = $asistencias[$alumno->id] ?? null;
                                            $h1 = $asistencia->hora_1 ?? 'presente';
                                            $h2 = $asistencia->hora_2 ?? 'presente';
                                            $h3 = $asistencia->hora_3 ?? 'presente';
                                            $h4 = $asistencia->hora_4 ?? 'presente';
                                            $porcentaje = $asistencia->porcentaje ?? 100;
                                        @endphp
                                        <tr class="alumno-row">
                                            <td class="text-secondary fw-bold">{{ $alumno->legajo }}</td>
                                            <td class="fw-medium" style="color: #1e293b;">
                                                {{ $alumno->persona->nombres }} {{ $alumno->persona->apellidos }}
                                            </td>
                                            
                                            <!-- Renderizado de los 4 Selectores -->
                                            @foreach(['hora_1' => $h1, 'hora_2' => $h2, 'hora_3' => $h3, 'hora_4' => $h4] as $name => $val)
                                                <td class="text-center">
                                                    <select name="asistencia[{{ $alumno->id }}][{{ $name }}]" class="form-select select-asistencia select-{{ $val }} mx-auto" style="width: 80px;" onchange="updateRow(this)">
                                                        <option value="presente" {{ $val == 'presente' ? 'selected' : '' }}>P</option>
                                                        <option value="ausente" {{ $val == 'ausente' ? 'selected' : '' }}>A</option>
                                                        <option value="justificado" {{ $val == 'justificado' ? 'selected' : '' }}>J</option>
                                                    </select>
                                                </td>
                                            @endforeach

                                            <td class="text-center">
                                                <span class="badge rounded-pill badge-porcentaje fs-6 
                                                    {{ $porcentaje == 100 ? 'bg-success' : ($porcentaje >= 50 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ rtrim(rtrim($porcentaje, '0'), '.') }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No hay alumnos matriculados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-2 small text-muted">
                                <strong>Referencias:</strong> P (Presente) | A (Ausente) | J (Justificado)
                            </div>
                        </div>

                        @if($alumnos->count() > 0)
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-nuevo text-white fw-bold px-5 py-2 fs-6 rounded-3">
                                    Guardar Asistencia
                                </button>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Lógica JavaScript para calcular el % visualmente sin recargar -->
    <script>
        function updateRow(selectElement) {
            // 1. Cambiar el color del select dependiendo de si es P, A o J
            selectElement.classList.remove('select-presente', 'select-ausente', 'select-justificado');
            selectElement.classList.add('select-' + selectElement.value);

            // 2. Calcular el nuevo porcentaje
            const row = selectElement.closest('.alumno-row');
            const selects = row.querySelectorAll('.select-asistencia');
            let asisValidas = 0;

            selects.forEach(s => {
                if(s.value === 'presente' || s.value === 'justificado') {
                    asisValidas++;
                }
            });

            const porcentaje = (asisValidas / 4) * 100;

            // 3. Actualizar el Badge del porcentaje
            const badge = row.querySelector('.badge-porcentaje');
            badge.textContent = porcentaje + '%';
            
            badge.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            if(porcentaje === 100) {
                badge.classList.add('bg-success');
            } else if(porcentaje >= 50) {
                badge.classList.add('bg-warning');
            } else {
                badge.classList.add('bg-danger');
            }
        }
    </script>
</x-app-layout>