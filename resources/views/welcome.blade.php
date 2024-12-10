<x-app-layout>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="cardis shadow p-3 text-center bgd text-white">
                    <a href="{{ route('proyectos.create') }}" class="btn btn-primary  btn-spacing"
                        style="font-size: 15px; height:100px;">
                        <i class="fas fa-plus mb-2" style="font-size: 24px;"></i>
                        Nuevo Proyecto
                    </a>
                    <!-- Botón Subir Excel -->
                    <button class="btn btnimg btn-spacing"
                        style="font-size: 15px; height:120px; width:180px; background-image: url('/images/Carpetade.png'); radius-border:5px;">
                        <div class="text-center">

                            <div style="margin-top:10px; color:white;">Subir Excel</div>
                            <i class="fas fa-file-excel mb-2 text-white" style="font-size: 15px; "></i>
                            <div><small class="text-white">Archivo.xlsx</small></div>
                        </div>
                    </button>
                </div>

            </div>
            <div class="col-md-5">
                <div class="cardis shadow p-3 bgd text-white">
                    <canvas id="chart" width="950" height="350"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="cardis shadow p-3 text-center bgd text-white cardu">
                    <img src="https://via.placeholder.com/100" class="rounded-circle perfilimg mb-3" alt="User">
                    <div>{{ Auth::user()->name }}</div>
                    <small>Persona</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow p-3 bgd text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Todos los proyectos</h6>
                        <div>
                            <input type="date" class="form-control estil d-inline w-auto">
                            <button class="btn estil btn-primary">Filtrar</button>
                        </div>
                    </div>
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Id</th>
                                <th>Nombre de la obra</th>
                                <th>Comunidad de la obra</th>
                                <th>Fecha de inicio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyectos as $proyecto)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $proyecto->Codigo }}</td>
                                    <td>{{ $proyecto->Nombreproyecto }}</td>
                                    <td>{{ $proyecto->Localidad }}</td>
                                    <td>{{ $proyecto->Fechainicio }}</td>

                                    <td>
                                        @if($proyecto->Estado == 'Faltante')
                                            <span class="badge bg-danger text-white">Faltante</span>
                                        @elseif($proyecto->Estado == 'En proceso')
                                            <span class="badge bg-warning text-dark">En proceso</span>
                                        @elseif($proyecto->Estado == 'Terminado')
                                            <span class="badge bg-success text-white">Terminado</span>
                                        @else
                                            <span class="badge bg-secondary text-white">{{ $proyecto->Estado }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-3 mb-3 bgd text-white">
                    <h6>Herramientas totales</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span>100%</span>
                        <canvas id="chart1" width="50" height="50"></canvas>
                    </div>
                    <small class="text-success">+1.5% Más personal que antes</small>
                </div>
                <div class="card shadow p-3 mb-3 bgd text-white">
                    <h6>Materiales totales</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span>100%</span>
                        <canvas id="chart2" width="50" height="50"></canvas>
                    </div>
                    <small class="text-success">+1.5% Más personal que antes</small>
                </div>
                <div class="card shadow p-3 bgd text-white">
                    <h6>Mano de obra</h6>
                    <div class="d-flex align-items-center justify-content-between">
                        <span>100%</span>
                        <canvas id="chart3" width="50" height="50"></canvas>
                    </div>
                    <small class="text-success">+1.5% Más personal que antes</small>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Llamada a la API para obtener los datos de la gráfica
            fetch('/datos-grafica')
                .then(response => response.json())
                .then(data => {
                    // Extraer etiquetas (fechas) y valores (cantidad de proyectos) del JSON
                    const labels = data.map(item => item.fecha);
                    const valores = data.map(item => item.cantidad);

                    // Crear la gráfica
                    const ctx = document.getElementById('chart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels, // Fechas
                            datasets: [{
                                label: 'Proyectos',
                                data: valores, // Cantidades
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.4,
                                pointBackgroundColor: 'white',
                                pointBorderColor: 'white',
                                pointHoverBackgroundColor: 'white',
                                pointHoverBorderColor: 'white',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        color: 'white'
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Fecha',
                                        color: 'white'
                                    },
                                    ticks: {
                                        color: 'white'
                                    },
                                    grid: {
                                        color: 'white'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Cantidad de Proyectos',
                                        color: 'white'
                                    },
                                    ticks: {
                                        color: 'white',
                                    },
                                    grid: {
                                        color: 'white'
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al cargar los datos:', error));
        </script>
    </div>
</x-app-layout>