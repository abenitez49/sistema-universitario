<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bg-panel { background-color: #f4f6f9; }
        .table th { color: #6c757d; font-weight: 500; font-size: 0.9rem; }
        .btn-nuevo { background-color: #2563eb; border: none; }
        .btn-nuevo:hover { background-color: #1d4ed8; }
    </style>

    <div class="min-vh-100 bg-panel py-5">
        <div class="container-fluid px-4" style="max-width: 1200px;">
            <h2 class="fw-bold mb-4" style="color: #0f172a;">Control de Asistencia</h2>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 15%;">Código</th>
                                    <th scope="col" style="width: 50%;">Materia</th>
                                    <th scope="col" style="width: 20%;">Alumnos Inscritos</th>
                                    <th scope="col" class="text-end" style="width: 15%;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($materias as $materia)
                                    <tr>
                                        <td class="text-secondary fw-medium">{{ $materia->codigo }}</td>
                                        <td class="fw-bold" style="color: #1e293b;">{{ $materia->nombre }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $materia->alumnos_count }} Alumnos
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($materia->alumnos_count > 0)
                                                <a href="{{ route('asistencias.show', $materia) }}" class="btn btn-nuevo text-white btn-sm fw-medium px-4 py-2 rounded-3">
                                                    Tomar Lista
                                                </a>
                                            @else
                                                <button class="btn btn-secondary btn-sm px-4 py-2 rounded-3" disabled>Sin Alumnos</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
</x-app-layout>