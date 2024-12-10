<?php

namespace App\Http\Controllers;
use App\Models\ReporteExcel;
use App\Models\Proyecto;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Imports\MaterialesImport;
use Maatwebsite\Excel\Facades\Excel;

class MaterialController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();

        // Importar los datos
        Excel::import(new MaterialesImport, $file);

        // Registrar el archivo en la tabla reportesexcel
        $lastCode = ReporteExcel::max('numeroexcel');
        $newCode = 'A' . str_pad(($lastCode ? intval(substr($lastCode, 1)) + 1 : 1), 2, '0', STR_PAD_LEFT);

        ReporteExcel::create([
            'nombre' => $fileName,
            'fechadecreacion' => now(),
            'numeroexcel' => $newCode,
        ]);

        return redirect()->route('materiales.index')->with('success', 'Materiales importados y registrados en reportesexcel correctamente.');
    }



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todas las obras desde la tabla proyectos
        $obras = Proyecto::select('Nombreproyecto')->get();

        // Obtener el nombre de la obra seleccionada desde el formulario
        $obraSeleccionada = $request->get('obra_id');

        // Filtrar los materiales por el nombre de la obra seleccionada
        if ($obraSeleccionada) {
            $materiales = Material::where('obra', $obraSeleccionada)->paginate(10);
        } else {
            // Si no se selecciona obra, mostrar todos los materiales
            $materiales = Material::paginate(10);
        }

        // Enviar los datos a la vista
        return view('materiales.index', compact('materiales', 'obras', 'obraSeleccionada'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('materiales.create');
    }

    public function distribuirMateriales(Request $request)
    {
        // Obtener el proyecto seleccionado
        $proyecto = Proyecto::where('Nombreproyecto', $request->proyecto)->first();

        if (!$proyecto) {
            // Si no se encuentra el proyecto, redirigir con un mensaje de error
            return redirect()->back()->with('error', 'Proyecto no encontrado.');
        }

        // Obtener los materiales para el proyecto seleccionado
        $materiales = Material::where('obra', $proyecto->Nombreproyecto)->get();

        // Calcular los días de duración del proyecto
        $fechaInicio = new \DateTime($proyecto->Fechainicio);
        $fechaFin = new \DateTime($proyecto->Fechafinal);
        $diasDeObra = $fechaFin->diff($fechaInicio)->days;

        // Distribuir la cantidad de material en los días de obra
        $cantidadDistribuida = [];
        foreach ($materiales as $material) {
            $cantidadDistribuida[$material->id] = $material->cantidad / $diasDeObra;
        }

        // Pasar los datos a la vista
        return view('proceso', compact('materiales', 'cantidadDistribuida', 'proyecto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required',
            'concepto' => 'required',
            'unidad' => 'required',
            'fecha' => 'required|date',
            'cantidad' => 'required|numeric',
            'obra' => 'required',
            'estado' => 'required|in:completo,incompleto,pendiente',
        ]);

        Material::create($request->all());
        return redirect()->route('materiales.index')->with('success', 'Material creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $material = Material::findOrFail($id); // Busca el material o lanza un error 404
        return view('materiales.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $material->update($request->all()); // Actualiza los campos con los datos enviados
        return redirect()->route('materiales.index')->with('success', 'Material actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el material por su id
        $material = Material::find($id);

        if ($material) {
            // Eliminar el material
            $material->delete();

            // Redirigir con un mensaje de éxito
            return redirect()->route('materiales.index')->with('success', 'Material eliminado con éxito.');
        } else {
            // Si no se encuentra el material, redirigir con un mensaje de error
            return redirect()->route('materiales.index')->with('error', 'El material no se encontró.');
        }
    }

    public function datosGraficas()
    {
        // Herramientas totales: Unidad "m3"
        $herramientas = Material::where('unidad', 'm3')
            ->selectRaw('concepto, SUM(cantidad) as total')
            ->groupBy('concepto')
            ->get();

        // Materiales totales: Unidad "HOR" o "HRS"
        $materiales = Material::whereIn('unidad', ['HOR', 'HRS'])
            ->selectRaw('concepto, SUM(cantidad) as total')
            ->groupBy('concepto')
            ->get();

        // Mano de obra: Unidad "JOR"
        $manoDeObra = Material::where('unidad', 'JOR')
            ->selectRaw('concepto, SUM(cantidad) as total')
            ->groupBy('concepto')
            ->get();

        // Devolver los datos en formato JSON
        return response()->json([
            'herramientas' => $herramientas,
            'materiales' => $materiales,
            'manoDeObra' => $manoDeObra,
        ]);
    }

}
