<x-app-layout>
    <style>
        .tabledarki th,
        .tabledarki td {
            color: white;
        }

        .position-relative {
            position: relative;
        }

        .position-absolute {
            position: absolute;
            pointer-events: none;
            /* Permite hacer clic en el input */
        }

        input[type="date"] {
            appearance: none;
            /* Elimina el estilo predeterminado */
            -webkit-appearance: none;
            /* Compatibilidad con WebKit */
            -moz-appearance: textfield;
            /* Compatibilidad con Firefox */
            border: 1px solid #b1b0bb4d;
            background-color: #060528;
            color: white;
            padding: 5px 40px 5px 10px;
            /* Espaciado para un ícono personalizado */
            border-radius: 5px;
            outline: none;
            position: relative;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            display: none;
            /* Oculta el ícono en navegadores WebKit */
        }
    </style>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="cardis shadow p-3 text-center bgd text-white">
                    <!-- Botón Crear Nuevo Proyecto -->
                    <a href="#" class="btn btn-primary btn-spacing" data-bs-toggle="modal" data-bs-target="#createModal"
                        style="font-size: 15px; height:100px; width:170px;">
                        <i class="fas fa-plus mb-2" style="font-size: 24px; margin-top:10px;"></i>
                        Nuevo Proyecto
                    </a>


                    <!-- Botón Subir Excel -->
                    <form action="{{ route('proyectos.import') }}" method="POST" enctype="multipart/form-data"
                        style="display: inline-block; margin-top: 0px;">
                        @csrf
                        <label for="file-import" class="btn btnimg btn-spacing"
                            style="font-size: 15px; height:120px; width:180px;  background-image: url('/images/Carpetade.png'); radius-border:5px;">
                            <div class="text-center">
                                <div style="margin-top:30px; color:white;">Subir Excel</div>
                                <i class="fas fa-file-excel mb-2 text-white" style="font-size: 15px;"></i>
                                <div><small class="text-white">Archivo.xlsx</small></div>
                            </div>
                            <input type="file" id="file-import" name="file" style="display: none;"
                                onchange="this.form.submit()">
                        </label>
                    </form>
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
                        <div class="d-flex align-items-center">
                            <button class="btn btn-primary me-2"
                                style="  background-color: #060528;  border: 1px solid #b1b0bb4d;"
                                onclick="filtrarFechas()">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <div class="position-relative">
                                <input type="date" class="form-control estil d-inline w-auto">
                                <i class="fas fa-calendar-alt position-absolute"
                                    style="right: 10px; top: 50%; transform: translateY(-50%); color: white;"></i>
                            </div>
                        </div>



                    </div>
                    <table class="table tabledarki ">
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
                <!-- Contenedor de Herramientas Totales -->
                <div class="card shadow p-3 mb-3 bgd text-white" style="max-width: 400px; width: 100%; height: 200px;">
                    <h6>Herramientas totales</h6>
                    <div class="d-flex align-items-center justify-content-between"
                        style="width: 100%; justify-content: center; height: 100%; align-items: center; margin-top:-40px;">
                        <span>100%</span>
                        <canvas id="chart1" class="small-pie-chart" width="400" height="200"
                            style="margin-right:40px; margin-top:-20px;"></canvas>
                    </div>
                    <small class="text-success">+1.5% Más personal que antes</small>
                </div>

                <!-- Contenedor de Materiales Totales -->
                <div class="card shadow p-3 mb-3 bgd text-white" style="max-width: 400px; width: 100%; height: 200px;">
                    <h6>Materiales totales</h6>
                    <div class="d-flex align-items-center justify-content-between"
                        style="width: 100%; justify-content: center; height: 100%; align-items: center; margin-top:-40px;">
                        <span>100%</span>
                        <canvas id="chart2" class="small-pie-chart" width="400" height="200"
                            style="margin-right:40px; margin-top:-20px;"></canvas>
                    </div>
                    <small class="text-success">+1.5% Más personal que antes</small>
                </div>

                <!-- Contenedor de Mano de Obra -->
                <div class="card shadow p-3 bgd text-white" style="max-width: 400px; width: 100%; height: 200px;">
                    <h6>Mano de obra</h6>
                    <div class="d-flex align-items-center justify-content-between"
                        style="width: 100%; justify-content: center; height: 100%; align-items: center;  margin-top:-40px;">
                        <span>100%</span>
                        <canvas id="chart3" class="small-pie-chart" width="400" height="200"
                            style="margin-right:40px; margin-top:-20px;"></canvas>
                    </div>
                    <small class="text-success">+1.5% Más personal que antes</small>
                </div>
            </div>



        </div>
        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Crear nuevo proyecto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="createFormContent" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>



            document.addEventListener('DOMContentLoaded', function () {
                const createModal = document.getElementById('createModal');

                createModal.addEventListener('show.bs.modal', function () {
                    const createFormContent = document.getElementById('createFormContent');
                    createFormContent.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>';

                    // Cargar el formulario de creación de proyectos
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
            // Gráfica de tipo "line" que ya tenías
            fetch('/datos-grafica')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.fecha); // Fechas
                    const valores = data.map(item => item.cantidad); // Cantidades

                    const ctx = document.getElementById('chart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Proyectos',
                                data: valores,
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

            // Nuevas gráficas de tipo "pie"
            fetch('{{ route("datos.graficas") }}')
                .then(response => response.json())
                .then(data => {
                    // Herramientas
                    const herramientasLabels = data.herramientas.map(item => item.concepto);
                    const herramientasData = data.herramientas.map(item => item.total);

                    const ctx1 = document.getElementById('chart1').getContext('2d');
                    new Chart(ctx1, {
                        type: 'pie',
                        data: {
                            labels: herramientasLabels,
                            datasets: [{
                                data: herramientasData,
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,

                                }
                            }
                        }
                    });

                    // Materiales
                    const materialesLabels = data.materiales.map(item => item.concepto);
                    const materialesData = data.materiales.map(item => item.total);

                    const ctx2 = document.getElementById('chart2').getContext('2d');
                    new Chart(ctx2, {
                        type: 'pie',
                        data: {
                            labels: materialesLabels,
                            datasets: [{
                                data: materialesData,
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,

                                }
                            }
                        }
                    });

                    // Mano de Obra
                    const manoObraLabels = data.manoDeObra.map(item => item.concepto);
                    const manoObraData = data.manoDeObra.map(item => item.total);

                    const ctx3 = document.getElementById('chart3').getContext('2d');
                    new Chart(ctx3, {
                        type: 'pie',
                        data: {
                            labels: manoObraLabels,
                            datasets: [{
                                data: manoObraData,
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,

                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error al cargar los datos de las gráficas:', error));
        </script>
    </div>
</x-app-layout>