<?php

namespace App\Imports;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProyectosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['codigo']) || empty($row['nombreproyecto'])) {
            // Si no hay datos en 'codigo' o 'nombreproyecto', la fila se ignorada
            return null;
        }

        // Convertir números de serie de Excel a fechas
        $fechainicio = isset($row['fechainicio']) && !empty($row['fechainicio']) ? Date::excelToDateTimeObject($row['fechainicio'])->format('Y-m-d') : null;
        $fechafinal = isset($row['fechafinal']) && !empty($row['fechafinal']) ? Date::excelToDateTimeObject($row['fechafinal'])->format('Y-m-d') : null;

        // Validar 'Nombreproyecto' para asegurarse de que no esté vacío
        if (empty($row['nombreproyecto'])) {
            $row['nombreproyecto'] = 'Sin nombre';
        }

        return new Proyecto([
            'Codigo' => $row['codigo'],
            'Nombreproyecto' => $row['nombreproyecto'],
            'Fechainicio' => $fechainicio,
            'Fechafinal' => $fechafinal,
            'Avance' => $row['avance'],
            'Municipiodelaobra' => $row['municipiodelaobra'],
            'Localidad' => $row['localidad'],
            'NoOficio' => $row['nooficio'],
            'Montototal' => $row['montototal'],
            'Abono' => $row['abono'],
            'Estado' => $row['estado'],
        ]);
    }

}
