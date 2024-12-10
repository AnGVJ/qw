@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #1a1a2e;
        color: #fff;
    }

    .custom-card {
        background-color: #181A32;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .custom-button {
        background-color: #181A32;
        border: none;
        height: 40px;
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .custom-button:hover {
        background-color: #6666b3;
    }

    .search-bar {
        background-color: #3a3a5e;
        border: none;
        color: #fff;
        border-radius: 8px;
    }

    .search-bar::placeholder {
        color: #b3b3cc;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table th,
    table td {
        color: #fff;
        padding: 8px;
        text-align: left;
    }

    table tr {
        border-bottom: 1px solid #fff;
    }

    .badge-terminado {
        background-color: green;
        color: white;
    }

    .badge-en-proceso {
        background-color: yellow;
        color: white;
    }

    .badge-pendiente {
        background-color: red;
        color: white;
    }

    .custom-select {
        background-color: #4c4c90;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 15px;
        width: 250px;
        font-size: 16px;
        transition: all 0.3s ease;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .custom-select:hover {
        background-color: #6666b3;
    }

    .custom-select:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(102, 102, 179, 0.8);
    }

    .obra-titulo {
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
    }
</style>

<div class="container my-5">
    <!-- Botones de acción -->
    <div class="d-flex justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img src="images/herramienta.png" alt="Icono"
                style="width: 30px; height: 30px; margin-right: 10px; margin-top:-15px;">
            <form method="GET" action="{{ route('materiales.index') }}">
                <select id="obraSelect" name="obra_id" class="custom-select" style=" background-color: #181A32;"
                    onchange="this.form.submit()">
                    <option value="">Seleccionar obra</option>
                    @foreach($obras as $obra)
                        <option value="{{ $obra->Nombreproyecto }}" {{ $obraSeleccionada == $obra->Nombreproyecto ? 'selected' : '' }}>
                            {{ $obra->Nombreproyecto }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <a href="{{ route('materiales.create') }}" class="custom-button">Agregar nueva herramienta o material</a>
        <button id="excelButton" class="custom-button" onclick="toggleForm()">Agregar desde Excel</button>
    </div>

    <!-- Formulario de importación -->
    <div id="excelForm" class="custom-card p-4 mb-4" style="display: none;">
        <form action="{{ route('materiales.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="file" class="form-label">Subir archivo Excel</label>
                <input type="file" name="file" class="form-control" required>
            </div>
            <button type="submit" class="custom-button">Importar</button>
        </form>
    </div>

    <!-- Barra de búsqueda -->
    <div class="custom-card p-4 mb-4">
        <input type="text" class="form-control search-bar" placeholder="Buscar insumo">
    </div>

    <!-- Tabla de materiales -->
    <div class="custom-card p-4">
        <table class="table table-borderless">
            <h1 class="obra-titulo text-center">
                {{ $obraSeleccionada ? $obraSeleccionada : 'Seleccione una obra' }}
            </h1>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Codigo</th>
                    <th>Nombre del material</th>
                    <th>Unidad</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materiales as $material)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $material->codigo}}</td>
                                <td>{{ $material->concepto }}</td>
                                <td>{{ $material->unidad }}</td>
                                <td>{{ $material->cantidad }}</td>

                                <td>
                                    <span class="badge" style="background-color: 
                                                                                                                                                                                                                                                                                                                                                                                            {{ $material->cantidad <= 0 ? 'green' :
                    ($material->faltante > 0 ? 'yellow' : 'red') }}; color: white;">
                                        {{ $material->cantidad <= 0 ? 'TERMINADO' :
                    ($material->faltante > 0 ? 'EN PROCESO' : 'PENDIENTE') }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('materiales.edit', $material->id) }}"
                                        class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('materiales.destroy', $material->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No hay materiales disponibles.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-3">
            {{ $materiales->links() }}
        </div>
    </div>
</div>

<script>
    function toggleForm() {
        var form = document.getElementById("excelForm");
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }

    function filterMaterialsByObra() {
        const obraId = document.getElementById('obraSelect').value;

        if (obraId) {
            window.location.href = `?obra_id=${obraId}`;
        } else {
            window.location.href = `?`;
        }
    }
</script>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>