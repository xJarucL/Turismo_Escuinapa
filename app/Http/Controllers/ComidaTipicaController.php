<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComidaTipica;
use App\Models\CategoriaComida;
use App\Models\ImagenComidaTipica;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;

class ComidaTipicaController extends Controller
{
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_comida' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'ingredientes' => 'required|string',
            'fk_cat_comida' => 'required|int',
            'img_comida' => 'required|image|mimes:jpeg,png,jpg',
        ],
        [
            'nom_comida.required' => 'El nombre del platillo de comida es obligatorio.',
            'img_comida.required' => 'La imagen del platillo es obligatoria.',
            'img_comida.image' => 'El archivo debe ser una imagen válida.',
            'img_comida.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_comida.max' => 'La imagen no debe exceder los 5MB.',
            'direccion.required' => 'La dirección es obligatoria.',
            'ingredientes.required' => 'Los ingredientes son obligatorios.',
            'fk_cat_comida.required' => 'La categoría del tipo de comida es obligatoria.'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            } else {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }
        }

        try {
            $comida = new ComidaTipica();
            $comida->nom_comida = $request->nom_comida;

            $slugNombreComida = Str::slug($comida->nom_comida);

            if ($request->hasFile('img_comida')) {
                $rutaImg = $request->file('img_comida')->store("comidas/{$slugNombreComida}", 'public');
                $comida->img_comida = $rutaImg;

            }


            $comida->descripcion = $request->descripcion;
            $comida->ingredientes = $request->ingredientes;
            $comida->fk_cat_comida = $request->fk_cat_comida;
            $comida->estatus = 1;

            $comida->save();

            LogHelper::log('info', 'Platillo de comida registrado exitosamente', ['Comida' => $request->nom_comida]);

            return response()->json(['comida_id' => $comida->pk_comida_tipica]);

        } catch (\Exception $e) {
            Log::error('Error al registrar platillo de comida: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error al registrar platillo de comida.'], 500);
            } else {
                return redirect()->back()->with('error', 'Error al registrar platillo de domida.');
            }
        }
    }

    public function cargarRegistro(){
        $categorias = CategoriaComida::all();
        return view('registrar_comida_tipica', compact('categorias'));
    }

    public function subirImagen(Request $request, $comidaId){
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png'
        ]);


        if ($request->hasFile('file')) {
            // Guardar archivo
            $comida = ComidaTipica::findOrFail($comidaId);
            $slugNombreComida = Str::slug($comida->nom_comida);
            $ruta = $request->file('file')->store("comidas/{$slugNombreComida}", 'public');

            $imagen = new ImagenComidaTipica();
            $imagen->pk_comida_tipica = $comidaId;
            $imagen->ruta = $ruta;
            $imagen->save();

            return response()->json([
                'ruta' => $ruta,
                'pk_img_comida_tipica' => $imagen->pk_img_comida_tipica,
            ], 200);
        }

        return response()->json(['error' => 'No se subió ninguna imagen'], 400);
    }


    public function eliminarImagen($imagenId)
    {
        $imagen = ImagenComidaTipica::find($imagenId);

        if (!$imagen) {
            return response()->json(['success' => false, 'mensaje' => 'Imagen no encontrada'], 404);
        }

        // Borrar archivo físico si existe
        if (Storage::exists($imagen->ruta)) {
            Storage::delete($imagen->ruta);
        }

        $imagen->delete();

        return response()->json(['success' => true, 'mensaje' => 'Imagen eliminada correctamente']);
    }

    public function mostrarComidas(){
        $comidas = ComidaTipica::whereNotNull('img_comida')
                        ->where('img_comida', '!=', '')
                         ->orderBy('updated_at', 'desc')
                        ->get();

        $comidas_public = ComidaTipica::where('estatus', 1) ->orderBy('updated_at', 'desc')->get();

        return view('lista_comidas_tipicas', compact('comidas', 'comidas_public'));
    }

    public function cambiarEstatus($id, Request $request){
        try {
            $comida = ComidaTipica::findOrFail($id);
            $comida->estatus = $request->estatus;
            $comida->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function mostrarComidaId()
    {
        $pk_comida_tipica = session('comida_seleccionada');

        if (!$pk_comida_tipica) {
            return redirect()->route('lista_comidas_tipicas')->with('error', 'Comida no seleccionada');
        }

        $comida = ComidaTipica::with('imagenes')->findOrFail($pk_comida_tipica);

        return view('inf_comida', compact('comida'));
    }

    public function mostrar_edicion()
{
    $pk_comida_tipica = session('comida_seleccionada');

    if (!$pk_comida_tipica) {
        return redirect()->route('lista_comidas_tipicas')->with('error', 'Comida no seleccionada');
    }

    $comida = ComidaTipica::with('imagenes')->findOrFail($pk_comida_tipica);
    $categorias = CategoriaComida::all();

    return view('editar_comidas', compact('comida', 'categorias'));
}

public function editar(Request $request)
{
    $pk_comida_tipica = session('comida_seleccionada');

    if (!$pk_comida_tipica) {
        return redirect()->route('lista_comidas_tipicas')->with('error', 'Comida no seleccionada.');
    }

    $comida = ComidaTipica::find($pk_comida_tipica);

    if (!$comida) {
        return redirect()->route('lista_comidas_tipicas')->with('error', 'Comida no encontrada.');
    }

    $request->validate([
        'nom_comida' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'ingredientes' => 'required|string',
        'fk_cat_comida' => 'required|int',
        'img_comida' => 'nullable|image|mimes:jpeg,png,jpg',
    ],
    [
        'nom_comida.required' => 'El nombre del platillo de comida es obligatorio.',
        'img_comida.required' => 'La imagen del platillo es obligatoria.',
        'img_comida.image' => 'El archivo debe ser una imagen válida.',
        'img_comida.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
        'img_comida.max' => 'La imagen no debe exceder los 2MB.',
        'direccion.required' => 'La dirección es obligatoria.',
        'ingredientes.required' => 'Los ingredientes son obligatorios.',
        'fk_cat_comida.required' => 'La categoría del tipo de comida es obligatoria.'
    ]);

    try {
        $nombreAnterior = $comida->nom_comida;
        $slugAnterior = Str::slug($nombreAnterior);
        $slugNuevo = Str::slug($request->nom_comida);

        // Si se subió una nueva imagen, eliminar la anterior
        if ($request->hasFile('img_comida') && $comida->img_comida) {
            File::delete(public_path('storage/' . $comida->img_comida));
            $comida->img_comida = null;
        }

        // Renombrar carpeta si el slug cambió
        if ($slugAnterior !== $slugNuevo) {
            $rutaAnterior = public_path("storage/comida_img/{$slugAnterior}");
            $rutaNueva = public_path("storage/comida_img/{$slugNuevo}");

            if (File::exists($rutaAnterior)) {
                File::copyDirectory($rutaAnterior, $rutaNueva);
                File::deleteDirectory($rutaAnterior);
            }

            ImagenComidaTipica::where('pk_comida_tipica', $comida->pk_comida_tipica)->get()->each(function ($img) use ($slugAnterior, $slugNuevo) {
                $rutaVieja = public_path("storage/" . $img->ruta);
                $nuevaRuta = str_replace("comida_img/{$slugAnterior}", "comida_img/{$slugNuevo}", $img->ruta);
                $rutaNueva = public_path("storage/" . $nuevaRuta);

                if (File::exists($rutaVieja)) {
                    File::ensureDirectoryExists(dirname($rutaNueva));
                    File::move($rutaVieja, $rutaNueva);
                }

                $img->ruta = $nuevaRuta;
                $img->save();
            });

            if ($comida->img_comida) {
                $nuevaRutaImg = str_replace("comida_img/{$slugAnterior}", "comida_img/{$slugNuevo}", $comida->img_comida);
                $comida->img_comida = $nuevaRutaImg;
            }
        }

        // Guardar nueva imagen si se subió
        if ($request->hasFile('img_comida')) {
            $rutaImagen = $request->file('img_comida')->store("comida_img/{$slugNuevo}", 'public');
            $comida->img_comida = $rutaImagen;
        }

        $comida->nom_comida = $request->nom_comida;
        $comida->descripcion = $request->descripcion;
        $comida->ingredientes = $request->ingredientes;
        $comida->fk_cat_comida = $request->fk_cat_comida;
        $comida->estatus = 1;

        $comida->touch();
        $comida->save();

        LogHelper::log('info', 'Comida editada correctamente', ['Comida' => $comida->nom_comida]);
        return redirect()->route('informacion_comida')->with('success', 'Comida editada correctamente.');

    } catch (\Exception $e) {
        LogHelper::log('error', 'Error al editar la comida', [
            'comida' => $request->nom_comida,
            'error' => $e->getMessage()
        ]);

        return back()->withErrors(['error' => 'Error al editar la comida: ' . $e->getMessage()]);
    }
}

public function obtenerImagenesComida(Request $request)
{
    $comidaId = session('comida_seleccionada');

    if (!$comidaId) {
        return response()->json(['error' => 'Comida no seleccionada'], 400);
    }

    $comida = ComidaTipica::with('imagenes')->findOrFail($comidaId);

    $imagenes = $comida->imagenes->map(function ($img) {
        return [
            'name' => basename($img->ruta),
            'size' => Storage::exists($img->ruta) ? Storage::size($img->ruta) : 0,
            'url'  => Storage::url($img->ruta),
            'id'   => $img->pk_img_comida_tipica,
        ];
    });

    return view('editar_img_comida_tipica', compact('comida'));
}


}
