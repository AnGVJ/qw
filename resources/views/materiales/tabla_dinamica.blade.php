<x-app-layout>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #131429;
            color: #ffffff;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #ffffff;
        }

        .container {
            width: 99%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1f1f2e;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .header div {
            text-align: center;
        }

        .header strong {
            display: block;
            color: #a8a8ff;
            margin-bottom: 5px;
        }

        select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #26263a;
            color: #ffffff;
            margin-bottom: 0px;
        }

        .progress-bar-container {
            position: relative;
            background-color: #3b3b5c;
            border-radius: 10px;
            width: 100%;
            height: 25px;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #28a745;
            height: 100%;
            width: 0%;
            transition: width 0.5s ease-in-out;
        }

        .progress-text {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1f1f2e;
            border-radius: 10px;
            overflow: hidden;
            table-layout: fixed;
        }

        th,
        td {
            font-size: 12px;
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #26263a;
            overflow: hidden;
            word-wrap: break-word;
        }

        th {
            background-color: #3b3b5c;
            color: #ffffff;
        }

        td {
            color: #a8a8ff;
        }

        tr:hover {
            background-color: #282847;
        }

        .no-materiales {
            color: #ff8080;
            font-weight: bold;
            text-align: center;
        }

        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 5px;
        }

        .button {
            height: 40px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .hidden-column {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .table-container {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 20px;
            max-height: 49vh;
            overflow-y: auto;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm 15mm;
            }

            body {
                background-color: white;
                color: black;
                -webkit-print-color-adjust: exact;
            }

            .container {
                padding: 0;
                margin: 0;
            }

            .header,
            .buttons,
            select,
            .progress-bar-container {
                display: none;
            }

            table {
                width: 100%;
                border: 1px solid #000;
                table-layout: auto;
            }

            th,
            td {
                border: 1px solid #000;
                color: black;
                background-color: white !important;
                padding: 8px;
                font-size: 10px;
                word-wrap: break-word;
                overflow: visible;
            }

            tr {
                page-break-inside: avoid;
            }

            .table-container {
                max-height: none;
                overflow: visible;
            }

            .hidden-column {
                display: table-cell !important;
            }
        }
    </style>

    <div class="container">
        <div class="header">
            <div class="medida">
                <strong>Planificación</strong>
                <span>{{ isset($proyectoSeleccionado) ? $proyectoSeleccionado->Nombreproyecto : 'N/A' }}</span>
            </div>
            <div>
                <strong id="avanceTotal">Avance Total: 0%</strong>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: 0%;">
                        <span class="progress-text">0%</span>
                    </div>
                </div>
            </div>
            <div>
                <strong>De la Semana</strong>
                <span>{{ isset($proyectoSeleccionado) ? $proyectoSeleccionado->Fechainicio : 'xx-xx-xxxx' }}</span>
            </div>
            <div>
                <strong>A la Semana</strong>
                <span>{{ isset($proyectoSeleccionado) ? $proyectoSeleccionado->Fechafinal : 'xx-xx-xxxx' }}</span>
            </div>
        </div>

        <form method="GET" action="{{ route('tabla.dinamica') }}">
            <label for="proyecto">Seleccionar Proyecto</label>
            <select id="proyecto" name="proyecto" required onchange="this.form.submit();">
                <option value="">Selecciona un proyecto</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id }}" {{ isset($proyectoSeleccionado) && $proyectoSeleccionado->id == $proyecto->id ? 'selected' : '' }}>
                        {{ $proyecto->Nombreproyecto }}
                    </option>
                @endforeach
            </select>
        </form>

        @if(isset($proyectoSeleccionado))
            @if(!empty($materialesPorSemana))
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                @foreach(array_keys($materialesPorSemana) as $index => $rango)
                                    <th>
                                        <input type="checkbox" onchange="toggleColumnVisibility(this, {{ $index }})">
                                        {{ $rango }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($materialesPorSemana as $materiales)
                                    <td>
                                        @if(count($materiales) > 0)
                                            <ul>
                                                @foreach($materiales as $material)
                                                    <li>{{ $material['concepto'] }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="no-materiales">No hay materiales</p>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p>No hay materiales disponibles para este proyecto.</p>
            @endif
        @endif

        <div class="buttons">
            <button class="button" onclick="imprimir()">Generar Reporte PDF</button>
        </div>
    </div>

    <script>
        function toggleColumnVisibility(checkbox, index) {
            const table = document.querySelector('table');
            const rows = table.querySelectorAll('tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('th, td');
                if (cells[index]) {
                    cells[index].classList.toggle('hidden-column', checkbox.checked);
                }
            });

            const proyectoId = document.getElementById('proyecto').value;
            const checkedColumns = JSON.parse(localStorage.getItem(proyectoId)) || {};
            checkedColumns[index] = checkbox.checked;
            localStorage.setItem(proyectoId, JSON.stringify(checkedColumns));

            calculateProgress();
        }

        function calculateProgress() {
            const checkboxes = document.querySelectorAll('th input[type="checkbox"]');
            const totalColumns = checkboxes.length;
            let selectedColumns = 0;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) selectedColumns++;
            });

            const progress = (selectedColumns / totalColumns) * 100;
            const progressBar = document.querySelector('.progress-bar');
            const progressText = document.querySelector('.progress-text');
            progressBar.style.width = progress + '%';
            progressText.textContent = progress.toFixed(1) + '%';
            document.getElementById('avanceTotal').textContent = `Avance Total: ${progress.toFixed(1)}%`;
        }

        function imprimir() {
            window.print();
        }

        window.onload = () => {
            const proyectoId = document.getElementById('proyecto').value;
            const checkedColumns = JSON.parse(localStorage.getItem(proyectoId)) || {};
            const checkboxes = document.querySelectorAll('th input[type="checkbox"]');

            checkboxes.forEach((checkbox, index) => {
                if (checkedColumns[index]) {
                    checkbox.checked = true;
                    toggleColumnVisibility(checkbox, index);
                }
            });
        };
    </script>
</x-app-layout>