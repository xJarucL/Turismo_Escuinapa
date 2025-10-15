<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventoAnual;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogHelper;
use App\Models\ImagenEventoAnual;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Illuminate\Support\Facades\Log;

class EventoAnualController extends Controller
{
    public function registrar(Request $request)
{
    $validator = Validator::make($request->all(), [
    'nom_evento' => 'required|string|max:255',
    'img_promocional' => 'required|image|mimes:jpeg,png,jpg',
    'tipo_fecha' => 'required|in:fija,variable,indefinida',

    'fecha_referencia' => [
    'required',
        function ($attribute, $value, $fail) use ($request) {
            if (!validarFormatoFechaReferencia($request->tipo_fecha, $value)) {
                if ($request->tipo_fecha === 'variable') {
                    $fail('El formato de la fecha debe ser como: 3-domingo-junio.');
                } elseif ($request->tipo_fecha === 'indefinida') {
                    $fail('El formato de la fecha debe ser como: 1-semana-junio.');
                } else {
                    $fail('El formato de la fecha debe ser como: 10-05.');
                }
            }
        }
    ],
    'descripcion' => 'required|string',
    'direccion' => 'required|string',
    'hora_evento' => 'required|date_format:H:i',
], [
    'nom_evento.required' => 'El nombre del evento es obligatorio.',
    'img_promocional.required' => 'La imagen de portada del evento es obligatoria.',
    'img_promocional.image' => 'El archivo debe ser una imagen válida.',
    'img_promocional.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
    'img_promocional.max' => 'La imagen no debe exceder los 5MB.',
    'descripcion.required' => 'La descripción del evento es obligatoria.',
    'hora_evento.required' => 'La hora del evento es obligatoria.',
    'direccion.required' => 'La dirección del evento es obligatoria.',
    'fecha_referencia.required' => 'La fecha del evento es obligatoria.',
    'hora_evento.required' => 'La hora del evento es obligatoria.',
    'hora_evento.date_format' => 'La hora del evento debe estar en formato HH:MM.',
    'fecha_referencia.required' => 'La fecha del evento es obligatoria.',
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
        $evento = new EventoAnual();
        $evento->nom_evento = $request->nom_evento;

        $slugNombreEvento = Str::slug($evento->nom_evento);

        if ($request->hasFile('img_promocional')) {
            $rutaImg = $request->file('img_promocional')->store("eventoAnual_img/{$slugNombreEvento}", 'public');
            $evento->img_promocional = $rutaImg;
        }

        $evento->tipo_fecha = $request->tipo_fecha;
        if ($request->tipo_fecha === 'variable' && Str::contains($request->fecha_referencia, 'semana')) {
            return back()->withErrors(['fecha_referencia' => 'Si usas "semana", debes seleccionar el tipo de fecha como indefinida.']);
        }

        if (in_array($request->tipo_fecha, ['variable', 'indefinida'])) {
            $evento->fecha_referencia = corregirFechaVariable($request->fecha_referencia);
        } else {
            $evento->fecha_referencia = $request->fecha_referencia;
        }

        $evento->descripcion = $request->descripcion;
        $evento->direccion = $request->direccion;
        $evento->hora_evento = $request->hora_evento;
        $evento->estatus = 1;

        $evento->save();

        return response()->json(['evento_id' => $evento->pk_evento_anual]);

    } catch (\Exception $e) {
        LogHelper::log('error', 'Error al registrar evento', [
            'Evento' => $request->nom_evento,
            'error' => $e->getMessage()
        ]);
        
        if ($request->expectsJson()) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
        return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}


    public function subirImagen(Request $request, $eventoAnualId)
{
    $request->validate([
        'file' => 'required|image|mimes:jpg,jpeg,png'
    ]);

    if ($request->hasFile('file')) {
        $evento = EventoAnual::findOrFail($eventoAnualId);
        $nombreEvento = Str::slug($evento->nom_evento);

        $ruta = $request->file('file')->store("eventoAnual_img/{$nombreEvento}", 'public');

        // Registrar en base de datos
        $imagen = new ImagenEventoAnual();
        $imagen->pk_evento_anual = $eventoAnualId;
        $imagen->ruta = $ruta;
        $imagen->save();

        return response()->json([
            'ruta' => $ruta,
            'pk_img_evento_anual' => $imagen->pk_img_evento_anual,
        ], 200);
    }
        return response()->json(['error' => 'No se subió ninguna imagen'], 400);
    }

    public function eliminarImagenEvento($imagenId)
    {
        $imagen = ImagenEventoAnual::where('pk_img_evento_anual', $imagenId)->first();

        if ($imagen) {
            Storage::disk('public')->delete($imagen->ruta);
            $imagen->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'mensaje' => 'Imagen no encontrada'], 404);
    }

        public function mostrar_edicion()
    {
        $pk_evento_anual = session('evento_seleccionado');

        if (!$pk_evento_anual) {
            return redirect()->route('lista_eventos')->with('error', 'Evento no seleccionado');
        }

        $evento = EventoAnual::findOrFail($pk_evento_anual);

        return view('editar_eventos', compact('evento'));
    }

    public function editar(Request $request)
{
    $pk_evento_anual = session('evento_seleccionado');

    if (!$pk_evento_anual) {
        return redirect()->route('lista_eventos')->with('error', 'Evento no seleccionado.');
    }

    $evento = EventoAnual::find($pk_evento_anual);

    if (!$evento) {
        return redirect()->route('lista_eventos')->with('error', 'Evento anual no encontrado.');
    }

    $requerirImagen = $evento->img_promocional ? 'nullable' : 'required';

    $request->validate([
    'nom_evento' => 'required|string|max:255',
    'img_promocional' => "$requerirImagen|image|mimes:jpeg,png,jpg",
    'tipo_fecha' => 'required|in:fija,variable,indefinida',
    'fecha_referencia' => [
        'required',
        function ($attribute, $value, $fail) use ($request) {
            if (!validarFormatoFechaReferencia($request->tipo_fecha, $value)) {
                if ($request->tipo_fecha === 'variable') {
                    $fail('El formato de la fecha debe ser como: 3-domingo-junio.');
                } elseif ($request->tipo_fecha === 'indefinida') {
                    $fail('El formato de la fecha debe ser como: 1-semana-junio.');
                } else {
                    $fail('El formato de la fecha debe ser como: 10-05.');
                }
            }
        }
    ],
    'descripcion' => 'required|string',
    'direccion' => 'required|string',
    'hora_evento' => 'required|date_format:H:i',
], [
    'nom_evento.required' => 'El nombre del evento es obligatorio.',
    'img_promocional.required' => 'La imagen de portada del evento es obligatoria.',
    'img_promocional.image' => 'El archivo debe ser una imagen válida.',
    'img_promocional.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
    'img_promocional.max' => 'La imagen no debe exceder los 5MB.',
    'descripcion.required' => 'La descripción del evento es obligatoria.',
    'hora_evento.required' => 'La hora del evento es obligatoria.',
    'hora_evento.date_format' => 'La hora del evento debe estar en formato HH:MM.',
    'direccion.required' => 'La dirección del evento es obligatoria.',
    'tipo_fecha.required' => 'El tipo de fecha es obligatorio.',
    'fecha_referencia.required' => 'La fecha del evento es obligatoria.',
    ]);

    try {
        $nombreAnterior = $evento->nom_evento;
        $slugAnterior = Str::slug($nombreAnterior);
        $slugNuevo = Str::slug($request->nom_evento);

        // Eliminar imagen anterior si existe en caso de venir una nueva
        if ($request->hasFile('img_promocional') && $evento->img_promocional) {
            File::delete(public_path('storage/' . $evento->img_promocional));
            $evento->img_promocional = null;
        }

        // Renombrar carpeta si cambió el slug
        if ($slugAnterior !== $slugNuevo) {
            $rutaAnterior = public_path("storage/eventoAnual_img/{$slugAnterior}");
            $rutaNueva = public_path("storage/eventoAnual_img/{$slugNuevo}");

            if (File::exists($rutaAnterior)) {
                File::moveDirectory($rutaAnterior, $rutaNueva);
            }

            if ($evento->img_promocional) {
                $evento->img_promocional = str_replace("eventoAnual_img/{$slugAnterior}", "eventoAnual_img/{$slugNuevo}", $evento->img_promocional);
            }

            ImagenEventoAnual::where('pk_evento_anual', $evento->pk_evento_anual)->get()->each(function ($img) use ($slugAnterior, $slugNuevo) {
                $rutaVieja = public_path("storage/" . $img->ruta);
                $nuevaRuta = str_replace("eventoAnual_img/{$slugAnterior}", "eventoAnual_img/{$slugNuevo}", $img->ruta);
                $rutaNueva = public_path("storage/" . $nuevaRuta);

                if (File::exists($rutaVieja)) {
                    File::ensureDirectoryExists(dirname($rutaNueva));
                    File::move($rutaVieja, $rutaNueva);
                }

                $img->ruta = $nuevaRuta;
                $img->save();
            });
        }

        // Procesar nueva imagen promocional con compresión
        if ($request->hasFile('img_promocional')) {
            if ($evento->img_promocional && File::exists(public_path('storage/' . $evento->img_promocional))) {
                File::delete(public_path('storage/' . $evento->img_promocional));
            }

            $rutaImagen = $request->file('img_promocional')->store("evento_img/{$slugNuevo}", 'public');
            $evento->img_promocional = $rutaImagen;
        }

        // Validar tipo y referencia
        if ($request->tipo_fecha === 'variable' && Str::contains($request->fecha_referencia, 'semana')) {
            return back()->withErrors(['fecha_referencia' => 'Si usas "semana", debes seleccionar el tipo de fecha como indefinida.']);
        }

        $evento->nom_evento = $request->nom_evento;
        $evento->tipo_fecha = $request->tipo_fecha;
        if (in_array($request->tipo_fecha, ['variable', 'indefinida'])) {
            $evento->fecha_referencia = corregirFechaVariable($request->fecha_referencia);
        } else {
            $evento->fecha_referencia = $request->fecha_referencia;
        }
        $evento->descripcion = $request->descripcion;
        $evento->direccion = $request->direccion;
        $evento->hora_evento = $request->hora_evento;

        $evento->touch();
        $evento->save();

        LogHelper::log('info', 'Evento anual editado correctamente', ['evento' => $evento->nom_evento]);
        return redirect()->route('informacion_evento')->with('success', 'Evento anual editado correctamente.');
    } catch (\Exception $e) {
        LogHelper::log('error', 'Error al editar evento anual', [
            'evento' => $request->nom_evento,
            'error' => $e->getMessage()
        ]);

        return back()->withErrors(['error' => 'Error al editar el evento anual: ' . $e->getMessage()]);
    }
}



}
