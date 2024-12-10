<?php
namespace App\Imports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MaterialesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $obra = null;

        foreach ($rows as $index => $row) {
            if ($index == 6) {
                $obra = $row[1]; // Ajusta la columna donde se encuentra el nombre de la obra
                continue;
            }

            if ($index >= 17) {
                if (is_null($row[0]) || $row[0] == "Código") {
                    continue;
                }

                $fecha = isset($row[3]) && $this->isValidDate($row[3])
                    ? Carbon::parse($row[3])->format('Y-m-d')
                    : null;

                Material::create([
                    'codigo' => $row[0] ?? null,                       // Columna "Código"
                    'concepto' => $row[1] ?? null,                       // Columna "Concepto"
                    'unidad' => $row[2] ?? null,                       // Columna "Unidad"
                    'fecha' => $fecha,                                // Columna "Fecha" convertida a formato 'Y-m-d'
                    'cantidad' => $row[4] ?? null,                       // Columna "Cantidad"
                    'obra' => $obra,                                 // Nombre de la obra
                ]);
            }
        }
    }

    /**
     * Verifica si un valor puede ser considerado como una fecha válida.
     */
    private function isValidDate($value)
    {
        try {
            Carbon::parse($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
