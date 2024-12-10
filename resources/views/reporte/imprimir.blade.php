@if(isset($proyectoSeleccionado) && !empty($materialesPorSemana))
    <!-- Tabla de impresión -->
    <h1>Planificación de Materiales</h1>
    <p><strong>Proyecto:</strong> {{ $proyectoSeleccionado->Nombreproyecto }}</p>
    <table>
        <thead>
            <tr>
                @foreach(array_keys($materialesPorSemana) as $index => $rango)
                    <th>{{ $rango }}</th>
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
                            <p>No hay materiales</p>
                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
@else
    <p>No hay datos para imprimir.</p>
@endif