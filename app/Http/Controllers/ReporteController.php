<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
class ReporteController extends Controller
{
    public function imprimir(Request $request)
    {
        // Obtén el proyecto seleccionado y los materiales (reutiliza la lógica que ya tienes)
        $proyectoSeleccionado = Proyecto::find($request->input('proyecto_id'));
        $materialesPorSemana = $this->obtenerMaterialesPorSemana($proyectoSeleccionado);

        return view('reporte.imprimir', compact('proyectoSeleccionado', 'materialesPorSemana'));
    }
    public function obtenerMaterialesPorSemana(Proyecto $proyecto)
    {
        // Lógica para obtener los materiales por semana, puede variar según tu estructura de base de datos
        // Ejemplo básico:
        $materialesPorSemana = [];

        // Aquí podrías usar tu propia lógica para obtener los materiales por semana
        // Ejemplo: Suponiendo que tienes una relación entre proyectos y materiales
        $materiales = $proyecto->materiales()->get();

        // Agrupar materiales por semana (puedes cambiarlo según tus necesidades)
        foreach ($materiales as $material) {
            $semana = $material->semana; // Suponiendo que tienes un campo "semana"
            $materialesPorSemana[$semana][] = $material;
        }

        return $materialesPorSemana;
    }

    public function mostrarVista(Request $request)
    {
        $proyectoSeleccionado = Proyecto::find($request->input('proyecto_id'));

        // Si el proyecto no se encuentra, puedes redirigir o mostrar un mensaje
        if (!$proyectoSeleccionado) {
            return redirect()->route('home')->with('error', 'Proyecto no encontrado');
        }

        $materialesPorSemana = $this->obtenerMaterialesPorSemana($proyectoSeleccionado);

        return view('tabla_dinamica', compact('proyectoSeleccionado', 'materialesPorSemana'));
    }


}
