<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LugaresInteres;
use App\Models\ImagenLugaresInteres;
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


class LugarInteresController extends Controller
{
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'direccion' => 'required|string',
            'url_google_resena' => 'nullable|string|url',
            'img_portada' => 'required|image|mimes:jpeg,png,jpg',
        ],
        [
            'nombre.required' => 'El nombre del lugar es obligatorio.',
            'img_portada.required' => 'La imagen del lugar es obligatoria.',
            'img_portada.image' => 'El archivo debe ser una imagen válida.',
            'img_portada.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_portada.max' => 'La imagen no debe exceder los 5MB.',
            'direccion.required' => 'La dirección es obligatoria.',
            'descripcion.required' => 'La descripcion es obligatoria.',
            'url_google_resena.url' => 'El enlace de Google Maps debe ser una URL válida.'
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
            $lugar = new LugaresInteres();
            $lugar->nombre = $request->nombre;

            $slugNombreLugar = Str::slug($lugar->nombre);

            if ($request->hasFile('img_portada')) {
               $rutaImg = $request->file('img_portada')->store("lugares/{$slugNombreLugar}", 'public');
                $lugar->img_portada = $rutaImg;
            }
            $lugar->descripcion = $request->descripcion;
            $lugar->direccion = $request->direccion;
            $lugar->url_google_resena = $request->input('url_google_resena') ?? null;
            $lugar->estatus = 1;

            $lugar->save();

            LogHelper::log('info', 'Lugar registrado exitosamente', ['Lugar' => $request->nombre]);

            return response()->json(['lugar_id' => $lugar->pk_lugar_interes]);

        } catch (\Exception $e) {
            Log::error('Error al registrar lugar de interés: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error al registrar lugar.'], 500);
            } else {
                return redirect()->back()->with('error', 'Error al registrar lugar.');
            }
        }
    }

    public function subirImagen(Request $request, $lugarInteresId){
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        if ($request->hasFile('file')) {
            $lugar = LugaresInteres::findOrFail($lugarInteresId);
            $slugNombreLugar = Str::slug($lugar->nombre);

            $ruta = $request->file('file')->store("lugares/{$slugNombreLugar}", 'public');

            // Registrar en la base de datos
            $imagen = new ImagenLugaresInteres();
            $imagen->pk_lugar_interes = $lugarInteresId;
            $imagen->ruta = $ruta;
            $imagen->save();

            return response()->json([
                'ruta' => $ruta,
                'pk_img_lugar_interes' => $imagen->pk_img_lugar_interes
            ], 200);
        }

        return response()->json(['error' => 'No se subió ninguna imagen'], 400);
    }


    public function mostrarLugares(){
        $lugares = LugaresInteres::whereNotNull('img_portada')
                        ->where('img_portada', '!=', '')
                         ->orderBy('updated_at', 'desc')
                        ->get();

        $lugares_public = LugaresInteres::where('estatus', 1) ->orderBy('updated_at', 'desc')->get();
        return view('lista_lugares', compact('lugares', 'lugares_public'));
    }

    public function mostrarLugarId()
    {
        $pk_lugar_interes = session('lugar_seleccionado');

        if (!$pk_lugar_interes) {
            return redirect()->route('lista_lugares')->with('error', 'Lugar no seleccionado');
        }

        $lugar = LugaresInteres::with('imagenes')->findOrFail($pk_lugar_interes);

        return view('inf_lugar', compact('lugar'));
    }

    public function cambiarEstatus($id, Request $request){
        try {
            $lugar = LugaresInteres::findOrFail($id);
            $lugar->estatus = $request->estatus;
            $lugar->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function mostrar_edicion(){
        $pk_lugar_interes = session('lugar_seleccionado');

        if (!$pk_lugar_interes) {
            return redirect()->route('lista_lugares')->with('error', 'Lugar no seleccionado');
        }

        $lugar = LugaresInteres::with('imagenes')->findOrFail($pk_lugar_interes);

        return view('editar_lugar', compact('lugar'));
    }

    public function editar(Request $request){
        $pk_lugar_interes = session('lugar_seleccionado');

        if (!$pk_lugar_interes) {
            return redirect()->route('lista_lugares')->with('error', 'Lugar no seleccionado.');
        }

        $lugar = LugaresInteres::find($pk_lugar_interes);

        if (!$lugar) {
            return redirect()->route('lista_lugares')->with('error', 'Lugar no encontrado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'direccion' => 'required|string',
            'url_google_resena' => 'nullable|string|url',
            'img_portada' => 'nullable|image|mimes:jpeg,png,jpg',
        ],
        [
            'nombre.required' => 'El nombre del lugar es obligatorio.',
            'img_portada.nullable' => 'La imagen del lugar es obligatoria.',
            'img_portada.image' => 'El archivo debe ser una imagen válida.',
            'img_portada.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_portada.max' => 'La imagen no debe exceder los 5MB.',
            'direccion.required' => 'La dirección es obligatoria.',
            'descripcion.required' => 'La descripcion es obligatoria.',
            'url_google_resena.url' => 'El enlace de Google Maps debe ser una URL válida.'
        ]);

        try {
            $nombreAnterior = $lugar->nombre;
            $slugAnterior = Str::slug($nombreAnterior);
            $slugNuevo = Str::slug($request->nombre);

            // Si se subió una nueva imagen, eliminar la anterior
            if ($request->hasFile('img_portada') && $lugar->img_portada) {
                File::delete(public_path('storage/' . $lugar->img_portada));
                $lugar->img_portada = null;
            }

            // Renombrar carpeta si el slug cambió
            if ($slugAnterior !== $slugNuevo) {
                $rutaAnterior = public_path("storage/lugar_img/{$slugAnterior}");
                $rutaNueva = public_path("storage/lugar_img/{$slugNuevo}");

                if (File::exists($rutaAnterior)) {
                    File::copyDirectory($rutaAnterior, $rutaNueva);
                    File::deleteDirectory($rutaAnterior);
                }

                ImagenLugaresInteres::where('pk_lugar_interes', $lugar->pk_lugar_interes)->get()->each(function ($img) use ($slugAnterior, $slugNuevo) {
                    $rutaVieja = public_path("storage/" . $img->ruta);
                    $nuevaRuta = str_replace("lugar_img/{$slugAnterior}", "lugar_img/{$slugNuevo}", $img->ruta);
                    $rutaNueva = public_path("storage/" . $nuevaRuta);

                    if (File::exists($rutaVieja)) {
                        File::ensureDirectoryExists(dirname($rutaNueva));
                        File::move($rutaVieja, $rutaNueva);
                    }

                    $img->ruta = $nuevaRuta;
                    $img->save();
                });

                if ($lugar->img_portada) {
                    $nuevaRutaImg = str_replace("lugar_img/{$slugAnterior}", "lugar_img/{$slugNuevo}", $lugar->img_portada);
                    $lugar->img_portada = $nuevaRutaImg;
                }
            }

            // Guardar nueva imagen si se subió (comprimida)
            if ($request->hasFile('img_portada')) {
                $rutaImagen = $request->file('img_portada')->store("lugar_img/{$slugNuevo}", 'public');
                $lugar->img_portada = $rutaImagen;
            }

            $lugar->nombre = $request->nombre;
            $lugar->descripcion = $request->descripcion;
            $lugar->direccion = $request->direccion;
            $lugar->url_google_resena = $request->url_google_resena;

            $lugar->touch();
            $lugar->save();

            LogHelper::log('info', 'Lugar editado correctamente', ['lugar' => $lugar->nombre]);
            return redirect()->route('informacion_lugar')->with('success', 'Lugar editado correctamente.');

        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al editar el lugar', [
                'lugar' => $request->nombre,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al editar el lugar: ' . $e->getMessage()]);
        }
    }
    public function obtenerImagenesLugar(Request $request){
        $lugarId = session('lugar_seleccionado');

        if (!$lugarId) {
            return response()->json(['error' => 'Lugar no seleccionado'], 400);
        }

        $lugar = LugaresInteres::with('imagenes')->findOrFail($lugarId);

        $imagenes = $lugar->imagenes->map(function ($img) {
            return [
                'name' => basename($img->ruta),
                'size' => Storage::exists($img->ruta) ? Storage::size($img->ruta) : 0,
                'url'  => Storage::url($img->ruta),
                'id'   => $img->pk_img_lugar_interes,
            ];
        });

        return view('editar_img_lugar', compact('lugar'));
    }

    public function eliminarImagen($imagenId)
    {
        $imagen = ImagenLugaresInteres::find($imagenId);

        if (!$imagen) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Imagen no encontrada'
            ], 404);
        }

        // Eliminar el archivo físico del disco
        if (Storage::disk('public')->exists($imagen->ruta)) {
            Storage::disk('public')->delete($imagen->ruta);
        }

        // Eliminar el registro en la base de datos
        $imagen->delete();

        return response()->json([
            'success' => true,
            'mensaje' => 'Imagen eliminada correctamente'
        ]);
    }
}
