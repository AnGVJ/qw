@extends('layouts.app') 

@section('content')
<div class="container mt-5">
    <div class="card bgd text-light">
        <div class="card-header">
            <h4>Reportes Excel</h4>
        </div>
        <div class="card-body">
            <table class="table bgd text-white table-hover">
                <thead>
                    <tr>
                        <th scope="col">Num</th>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre de Excel</th>
                        <th scope="col">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportes as $reporte)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $reporte->numeroexcel }}</td>
                            <td>{{ $reporte->nombre }}</td>
                            <td>{{ $reporte->fechadecreacion }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <button class="btn btn-danger">Generar Reporte</button>
</div>
@endsection