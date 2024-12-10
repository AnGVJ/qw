@extends('layouts.app')

@section('content')
<div class="container-fluid p-4 text-light" style="background-color: #060528 !important;">
    <!-- Cabecera -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex bgd align-items-center" style="width: 300px; border-radius: 5px;">
            <div class="rounded-circle p-3 me-3" style="background-color: #060528 !important;">
                <img src="images/proyec.png" alt="Icono" class="img-fluid" style="width: 70px;">
            </div>
            <div class="nuevoc">
                <h4 class="mb-0">TOTAL DE PROYECTOS</h4>
                <p class="mb-0 text-center" style="color:white;">{{ $totalProyectos }} proyectos</p>

            </div>
        </div>
        <div class="d-flex align-items-center">
            <a href="#" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#createModal">Crear nuevo
                proyecto</a>
            <!-- Formulario Importar -->
            <form action="{{ route('proyectos.import') }}" method="POST" enctype="multipart/form-data"
                style="display: inline-block;">
                @csrf
                <label for="file-import" class="btn btn-primary mb-0">Subir nuevo proyecto</label>
                <input type="file" id="file-import" name="file" style="display: none;" onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <!-- Buscador y filtros -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <input type="text" class="form-control w-50 me-3 bgd text-light" placeholder="Buscar proyecto">
        <div class="d-flex align-items-center">
            <input type="date" class="form-control bgd text-light me-2">
            <select class="form-control bgd text-light me-2">
                <option>Estado</option>
                <option value="Terminado">Terminado</option>
                <option value="En proceso">En proceso</option>
                <option value="Faltante">Faltante</option>
            </select>
            <button class="btn btn-secondary">Más filtros</button>
        </div>
    </div>

    <!-- Tabla -->
    <table class="table bgd" style="color: white;">
        <thead>
            <tr>
                <th>No</th>
                <th>Id</th>
                <th>Nombre de la obra</th>
                <th>Municipio de la obra</th>
                <th>Localidad de la obra</th>
                <th>Fecha de inicio</th>
                <th>Fecha de término</th>
                <th>N° de Oficio</th>
                <th>Monto total de la obra</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($proyectos as $proyecto)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $proyecto->id }}</td>
                    <td><a href="{{ route('seguimiento.show', $proyecto->id) }}"
                            style="color:white;">{{ $proyecto->Nombreproyecto }}</a></td>
                    <td>{{ $proyecto->Municipiodelaobra }}</td>
                    <td>{{ $proyecto->Localidad }}</td>
                    <td>{{ $proyecto->Fechainicio }}</td>
                    <td>{{ $proyecto->Fechafinal }}</td>
                    <td>{{ $proyecto->NoOficio }}</td>
                    <td>${{ number_format($proyecto->Montototal, 2) }}</td>


                    <td>
                        @if($proyecto->Estado == 'Terminado')
                            <span class="badge bg-success">●</span>
                        @elseif($proyecto->Estado == 'En proceso')
                            <span class="badge bg-warning">●</span>
                        @else
                            <span class="badge bg-danger">●</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('proyectos.edit', $proyecto->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form action="{{ route('proyectos.destroy', $proyecto->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Botón Exportar -->
    <div class="text-end">

    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Crear nuevo proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí se cargará el contenido del formulario -->
                <div id="createFormContent" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const createModal = document.getElementById('createModal');

        createModal.addEventListener('show.bs.modal', function () {
            const createFormContent = document.getElementById('createFormContent');
            createFormContent.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>';

            // Carga el contenido del formulario usando AJAX
            fetch("{{ route('proyectos.create') }}")
                .then(response => response.text())
                .then(html => {
                    createFormContent.innerHTML = html;
                })
                .catch(error => {
                    createFormContent.innerHTML = '<p class="text-danger">Error al cargar el formulario.</p>';
                    console.error(error);
                });
        });
    });
</script>

@endsection