<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurante;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogHelper;
use App\Models\ImagenRestaurante;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;


class RestauranteController extends Controller
{
    public function registrar(Request $request){
        // Validar datos
    $validator = Validator::make($request->all(), [
        'nom_restaurante' => 'required|string|max:255',
        'direccion' => 'required|string',
        'hora_apertura' => 'required|date_format:H:i',
        'hora_cierre' => 'required|date_format:H:i|after:hora_apertura',
        'descripcion' => 'required|string',
        'img_promocional' => 'required|image|mimes:jpeg,png,jpg',
        'tel_restaurante' => ['required', 'regex:/^\d{10}$/'],
        'email_restaurante' => 'required|email|max:255',
        'url_google_resena' => 'nullable|url',
    ], [
        'nom_restaurante.required' => 'El nombre del restaurante es obligatorio.',
        'direccion.required' => 'La dirección es obligatoria.',
        'hora_apertura.required' => 'La hora de apertura es obligatoria.',
        'hora_apertura.date_format' => 'La hora de apertura debe estar en formato HH:MM.',
        'hora_cierre.required' => 'La hora de cierre es obligatoria.',
        'hora_cierre.date_format' => 'La hora de cierre debe estar en formato HH:MM.',
        'hora_cierre.after' => 'La hora de cierre debe ser posterior a la hora de apertura.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'img_promocional.required' => 'La imagen promocional es obligatoria.',
        'img_promocional.image' => 'El archivo debe ser una imagen válida.',
        'img_promocional.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
        'img_promocional.max' => 'La imagen no debe exceder los 5MB.',
        'tel_restaurante.required' => 'El teléfono del restaurante es obligatorio.',
        'contacto.regex' => 'El contacto debe contener exactamente 10 dígitos numéricos, sin letras ni símbolos.',
        'email_restaurante.required' => 'El correo electrónico es obligatorio.',
        'email_restaurante.email' => 'Debe ingresar un correo electrónico válido.',
        'url_google_resena.url' => 'El enlace de Google debe ser una URL válida.',
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

        // Guardar en la base de datos
        $restaurante = new Restaurante();
        $restaurante->nom_restaurante=$request->nom_restaurante;
        $restaurante->direccion=$request->direccion;
        $restaurante->hora_apertura=$request->hora_apertura;
        $restaurante->hora_cierre=$request->hora_cierre;
        $restaurante->descripcion=$request->descripcion;

        $slugNombreRestaurante = Str::slug($restaurante->nom_restaurante);


        if ($request->hasFile('img_promocional')) {
            $rutaImg = $request->file('img_promocional')->store("restaurantes_img/{$slugNombreRestaurante}", 'public');
            $restaurante->img_promocional = $rutaImg;
        }

        $restaurante->tel_restaurante=$request->tel_restaurante;
        $restaurante->email_restaurante=$request->email_restaurante;
        $restaurante->url_google_reseña=$request->url_google_resena;
        $restaurante->estatus= 1;

        $restaurante->save();

        LogHelper::log('info', 'Restaurante registrado exitosamente', ['Restaurante' => $request->nom_restaurante]);

         // Respuesta JSON con el ID del evento (para el Dropzone)
        return response()->json(['restaurante_id' => $restaurante->pk_restaurante]);

    } catch (\Exception $e) {
        LogHelper::log('error', 'Error al registrar restaurante', [
            'Restaurante' => $request->nom_restaurante,
            'error' => $e->getMessage()
        ]);

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Error al registrar el restaurante.'], 500);
        }


        return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }

    }

    public function mostrarRestaurantes(){
        $restaurantes = Restaurante::whereNotNull('img_promocional')
                        ->where('img_promocional', '!=', '')
                         ->orderBy('updated_at', 'desc')
                        ->get();

        $restaurantes_public = Restaurante::where('estatus', 1) ->orderBy('updated_at', 'desc')->get();

        return view('lista_restaurantes', compact('restaurantes', 'restaurantes_public'));
    }

    public function cambiarEstatus($id, Request $request){
    try {
        $restaurante = Restaurante::findOrFail($id);
        $restaurante->estatus = $request->estatus;
        $restaurante->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

    public function buscar(Request $request)
    {
        $q = $request->query('q');

        $restaurantes = Restaurante::where('nom_restaurante', 'like', "%{$q}%")
            ->get(['pk_restaurante', 'nom_restaurante']);

        return response()->json($restaurantes);
    }

    public function subirImagen(Request $request, $restauranteId){
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        if ($request->hasFile('file')) {
            $restaurante = Restaurante::findOrFail($restauranteId);
            $nombreRestaurante = Str::slug($restaurante->nom_restaurante);

            $ruta = $request->file('file')->store("restaurante_img/{$nombreRestaurante}", 'public');

            $imagen = new ImagenRestaurante();
            $imagen->pk_restaurante = $restauranteId;
            $imagen->ruta = $ruta;
            $imagen->save();

            return response()->json([
                'ruta' => $ruta,
                'pk_img_restaurante' => $imagen->pk_img_restaurante
            ], 200);
        }
        return response()->json(['error' => 'No se subió ninguna imagen'], 400);
    }

    public function eliminarImagen($imagenId)
{
    $imagen = ImagenRestaurante::find($imagenId);

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

    public function mostrarRestauranteId()
    {
        $pk_restaurante = session('restaurante_seleccionado');

        if (!$pk_restaurante) {
            return redirect()->route('lista_restaurantes')->with('error', 'Restaurante no seleccionado');
        }

        $restaurante = Restaurante::with('imagenes')->findOrFail($pk_restaurante);

        return view('informacion_restaurante', compact('restaurante'));
    }

    public function mostrar_edicion()
    {
        $pk_restaurante = session('restaurante_seleccionado');

        if (!$pk_restaurante) {
            return redirect()->route('lista_restaurantes')->with('error', 'Restaurante no seleccionado');
        }

        $restaurante = Restaurante::findOrFail($pk_restaurante);

        return view('editar_restaurantes', compact('restaurante'));
    }

    public function editar(Request $request){
        $pk_restaurante = session('restaurante_seleccionado');

        if (!$pk_restaurante) {
            return redirect()->route('lista_restaurantes')->with('error', 'Restaurante no seleccionado.');
        }

        $restaurante = Restaurante::find($pk_restaurante);

        if (!$restaurante) {
            return redirect()->route('lista_restaurantes')->with('error', 'Restaurante no encontrado.');
        }

        $request->validate([
            'nom_restaurante' => 'required|string|max:255',
            'img_promocional' => 'nullable|image|mimes:jpeg,png,jpg',
            'direccion' => 'required|string',
            'hora_apertura' => 'required|date_format:H:i',
            'hora_cierre' => 'required|date_format:H:i|after:hora_apertura',
            'descripcion' => 'required|string',
            'tel_restaurante' => ['required', 'regex:/^\d{10}$/'],
            'email_restaurante' => 'required|email|max:255',
            'url_google_reseña' => 'nullable|url',
        ],
        [
            'nom_restaurante.required' => 'El nombre del restaurante es obligatorio.',
            'img_promocional.image' => 'El archivo debe ser una imagen válida.',
            'img_promocional.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_promocional.max' => 'La imagen no debe exceder los 2MB.',
            'direccion.required' => 'La dirección del restaurante es obligatoria.',
            'hora_apertura.required' => 'La hora de apertura es obligatoria.',
            'hora_apertura.date_format' => 'La hora de apertura debe tener el formato HH:mm.',
            'hora_cierre.required' => 'La hora de cierre es obligatoria.',
            'hora_cierre.date_format' => 'La hora de cierre debe tener el formato HH:mm.',
            'hora_cierre.after' => 'La hora de cierre debe ser posterior a la hora de apertura.',
            'descripcion.required' => 'La descripción del restaurante es obligatoria.',
            'tel_restaurante.required' => 'El teléfono del restaurante es obligatorio.',
            'contacto.regex' => 'El contacto debe contener exactamente 10 dígitos numéricos, sin letras ni símbolos.',
            'email_restaurante.required' => 'El email del restaurante es obligatorio.',
            'email_restaurante.email' => 'El email debe ser válido.',
            'url_google_reseña.url' => 'La URL de Google reseñas debe ser válida.',
        ]);

        try {
            $nombreAnterior = $restaurante->nom_restaurante;
            $slugAnterior = Str::slug($nombreAnterior);
            $slugNuevo = Str::slug($request->nom_restaurante);

            // Eliminar imagen anterior si se sube una nueva
            if ($request->hasFile('img_promocional') && $restaurante->img_promocional) {
                File::delete(public_path('storage/' . $restaurante->img_promocional));
                $restaurante->img_promocional = null;
            }

            // Renombrar carpeta si cambia el slug
            if ($slugAnterior !== $slugNuevo) {
                $rutaAnterior = public_path("storage/restaurante_img/{$slugAnterior}");
                $rutaNueva = public_path("storage/restaurante_img/{$slugNuevo}");

                if (File::exists($rutaAnterior)) {
                    File::copyDirectory($rutaAnterior, $rutaNueva);
                    File::deleteDirectory($rutaAnterior);
                }

                ImagenRestaurante::where('pk_restaurante', $restaurante->pk_restaurante)->get()->each(function ($img) use ($slugAnterior, $slugNuevo) {
                    $rutaVieja = public_path("storage/" . $img->ruta);
                    $nuevaRuta = str_replace("restaurante_img/{$slugAnterior}", "restaurante_img/{$slugNuevo}", $img->ruta);
                    $rutaNueva = public_path("storage/" . $nuevaRuta);

                    if (File::exists($rutaVieja)) {
                        File::ensureDirectoryExists(dirname($rutaNueva));
                        File::move($rutaVieja, $rutaNueva);
                    }

                    $img->ruta = $nuevaRuta;
                    $img->save();
                });

                if ($restaurante->img_promocional) {
                    $nuevaRutaImg = str_replace("restaurante_img/{$slugAnterior}", "restaurante_img/{$slugNuevo}", $restaurante->img_promocional);
                    $restaurante->img_promocional = $nuevaRutaImg;
                }
            }

            // Subir y comprimir nueva imagen promocional
            if ($request->hasFile('img_promocional')) {
                $rutaImagen = $request->file('img_promocional')->store("restaurante_img/{$slugNuevo}", 'public');
                $restaurante->img_promocional = $rutaImagen;
            }


            $restaurante->nom_restaurante = $request->nom_restaurante;
            $restaurante->direccion = $request->direccion;
            $restaurante->hora_apertura = $request->hora_apertura;
            $restaurante->hora_cierre = $request->hora_cierre;
            $restaurante->descripcion = $request->descripcion;
            $restaurante->tel_restaurante = $request->tel_restaurante;
            $restaurante->email_restaurante = $request->email_restaurante;
            $restaurante->url_google_reseña = $request->url_google_reseña;

            $restaurante->touch();
            $restaurante->save();

            LogHelper::log('info', 'Restaurante editado correctamente', ['restaurante' => $restaurante->nom_restaurante]);
            return redirect()->route('informacion_restaurante')->with('success', 'Restaurante editado correctamente.');

        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al editar el restaurante', [
                'restaurante' => $request->nom_restaurante,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al editar el restaurante: ' . $e->getMessage()]);
        }
    }

    public function obtenerImagenesRestaurante(Request $request)
    {
        $restauranteId = session('restaurante_seleccionado');

        if (!$restauranteId) {
            return response()->json(['error' => 'Restaurante no seleccionado'], 400);
        }

        $restaurante = Restaurante::with('imagenes')->findOrFail($restauranteId);

        $imagenes = $restaurante->imagenes->map(function ($img) {
            return [
                'name' => basename($img->ruta),
                'size' => Storage::exists($img->ruta) ? Storage::size($img->ruta) : 0,
                'url'  => Storage::url($img->ruta),
                'id'   => $img->pk_img_restaurante,
            ];
        });

        return view('editar_img_restaurantes', compact('restaurante'));
    }

}
