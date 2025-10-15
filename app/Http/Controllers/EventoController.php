<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogHelper;
use App\Models\ImagenEvento;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\EventoAnual;
use App\Models\ImagenEventoAnual;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;

class EventoController extends Controller
{
public function registrar(Request $request)
{
    // Validar datos
    $validator = Validator::make($request->all(), [
        'nom_evento' => 'required|string|max:255',
        'img_promocional' => 'required|image|mimes:jpeg,png,jpg',
        'fecha_hora' => 'required|date',
        'descripcion' => 'required|string',
        'direccion'      => 'required|string',
        'hora_evento'    => 'required|date_format:H:i',
    ],
    [
        'nom_evento.required' => 'El nombre del evento es obligatorio.',
        'img_promocional.required' => 'La imagen de portada del evento es obligatoria.',
        'img_promocional.image' => 'El archivo debe ser una imagen válida.',
        'img_promocional.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
        'img_promocional.max' => 'La imagen no debe exceder los 5MB.',
        'fecha_hora.required' => 'La fecha y hora del evento son obligatorias.',
        'fecha_hora.date' => 'La fecha y hora deben ser válidas.',
        'descripcion.required' => 'La descripción del evento es obligatoria.',
        'hora_evento.required' => 'La hora del evento es obligatoria.',
        'hora_evento.date_format' => 'La hora del evento debe estar en formato HH:MM.',
        'direccion.required' => 'La dirección del evento es obligatoria.',
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
        $evento = new Evento();
        $evento->nom_evento = $request->nom_evento;

        $slugNombreEvento = Str::slug($evento->nom_evento);

        if ($request->hasFile('img_promocional')) {
            $rutaImg = $request->file('img_promocional')->store("evento_img/{$slugNombreEvento}", 'public');
            $evento->img_promocional = $rutaImg;
        }

        $evento->fecha_hora = $request->fecha_hora;
        $evento->descripcion = $request->descripcion;
        $evento->direccion = $request->direccion;
        $evento->hora_evento =  $request->hora_evento;

        $evento->estatus = 1;

        $evento->save();

        LogHelper::log('info', 'Evento registrado exitosamente', ['Evento' => $request->nom_evento]);

        // Respuesta JSON con el ID del evento (para el Dropzone)
        return response()->json(['evento_id' => $evento->pk_evento]);

    } catch (\Exception $e) {
        LogHelper::log('error', 'Error al registrar evento', [
            'Evento' => $request->nom_evento,
            'error' => $e->getMessage()
        ]);

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Error al registrar el evento.'], 500);
        }


        return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
    }
}


    public function mostrarEventos(){
        $eventos = Evento::whereNotNull('img_promocional')
                        ->where('img_promocional', '!=', '')
                        ->orderBy('updated_at', 'desc')
                        ->get();
        $eventosAnuales = EventoAnual::whereNotNull('img_promocional')
                        ->where('img_promocional', '!=', '')
                        ->orderBy('updated_at', 'desc')
                        ->get();

        $eventos_public = Evento::where('estatus', 1) ->orderBy('updated_at', 'desc')->get();
        $eventos_anuales_public = EventoAnual::where('estatus', 1) ->orderBy('updated_at', 'desc')->get();
        return view('lista_eventos', compact('eventos', 'eventosAnuales', 'eventos_public', 'eventos_anuales_public'));
    }

    public function mostrarCalendario(Request $request) {
    $anio = $request->anio ?? date('Y');

    $mesesConEventosRegulares = Evento::where('estatus', 1)
    ->whereYear('fecha_hora', $anio)
    ->selectRaw('MONTH(fecha_hora) as mes')
    ->distinct()
    ->pluck('mes')
    ->toArray();

    // Obtener meses con eventos anuales para el año
    $eventosAnuales = EventoAnual::where('estatus', 1)->get();

    $mesesConEventosAnuales = $eventosAnuales->map(function($ev) use ($anio) {
        if ($ev->tipo_fecha === 'fija') {
            return (int)explode('-', $ev->fecha_referencia)[1];
        } elseif ($ev->tipo_fecha === 'variable') {
            $fecha = calcularFechaDesdeVariable($ev->fecha_referencia, $anio);
            return $fecha ? (int)explode('-', $fecha)[1] : null;
        }
        return null;
    })->filter()->unique()->values()->toArray();

    // Meses que tienen algún evento (regular o anual)
    $mesesConEventos = array_unique(array_merge($mesesConEventosRegulares, $mesesConEventosAnuales));

    $eventos = Evento::select('pk_evento', 'nom_evento', 'fecha_hora', 'img_promocional')
        ->where('estatus', 1)
        ->orderBy('fecha_hora', 'asc')
        ->get();

    $eventosAnuales = EventoAnual::select('pk_evento_anual', 'nom_evento', 'tipo_fecha', 'fecha_referencia', 'img_promocional')
        ->where('estatus', 1)
        ->get()
        ->map(function($ev) use ($anio) {
            if ($ev->tipo_fecha === 'fija') {
                $ev->mes_dia = $ev->fecha_referencia;
            } elseif ($ev->tipo_fecha === 'variable') {
                $ev->mes_dia = calcularFechaDesdeVariable($ev->fecha_referencia, $anio);
            } else {
                $ev->mes_dia = null;
            }
            return $ev;
        })
        ->filter(fn($ev) => $ev->mes_dia !== null);

        $mesesConEventos = array_values($mesesConEventos);

    return view('calendario_eventos', compact('eventos', 'eventosAnuales', 'anio', 'mesesConEventos'));
}

public function getEventosPorMes($anio, $mes) {
    if ($mes < 1 || $mes > 12) {
        return response()->json(['error' => 'Mes inválido'], 400);
    }
    $eventos = Evento::select('pk_evento', 'nom_evento', 'fecha_hora', 'img_promocional')
        ->where('estatus', 1)
        ->whereYear('fecha_hora', $anio)
        ->whereMonth('fecha_hora', $mes)
        ->orderBy('fecha_hora', 'asc')
        ->get();

    $eventosAnuales = EventoAnual::select('pk_evento_anual', 'nom_evento', 'tipo_fecha', 'fecha_referencia', 'img_promocional')
        ->where('estatus', 1)
        ->where('tipo_fecha', '!=', 'indefinida')
        ->get()
        ->map(function($ev) use ($anio, $mes) {
            try {
                if ($ev->tipo_fecha === 'fija') {
                    $ev->mes_dia = $ev->fecha_referencia;
                } elseif ($ev->tipo_fecha === 'variable') {
                    $ev->mes_dia = calcularFechaDesdeVariable($ev->fecha_referencia, $anio);
                } else {
                    $ev->mes_dia = null;
                }
            } catch (\Exception $e) {
                \Log::error("Error en calcularFechaDesdeVariable para evento anual {$ev->nom_evento}: " . $e->getMessage());
                $ev->mes_dia = null;
            }
            return $ev;
        })
        ->filter(fn($ev) => $ev->mes_dia !== null)
        ->filter(function($ev) use ($mes) {
            $eventoMes = explode('-', $ev->mes_dia)[1] ?? null;
            return $eventoMes == str_pad($mes, 2, '0', STR_PAD_LEFT);
        })
        ->values();

    return response()->json([
        'eventos' => $eventos,
        'eventosAnuales' => $eventosAnuales
    ]);
}


    public function cambiarEstatus($id, Request $request){
    try {
        $tipo = $request->input('tipo', 'normal');

        if ($tipo === 'anual') {
            $evento = EventoAnual::findOrFail($id);
        } else {
            $evento = Evento::findOrFail($id);
        }

        $evento->estatus = $request->estatus;
        $evento->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

    public function subirImagen(Request $request, $eventoId)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        if ($request->hasFile('file')) {
            $evento = Evento::findOrFail($eventoId);
            $nombreEvento = Str::slug($evento->nom_evento);
            $ruta = $request->file('file')->store("evento_img/{$nombreEvento}", 'public');

            $imagen = new ImagenEvento();
            $imagen->pk_evento = $eventoId;
            $imagen->ruta = $ruta;
            $imagen->save();

            return response()->json([
                'ruta' => $ruta,
                'pk_img_evento' => $imagen->pk_img_evento
            ], 200);
        }

        return response()->json(['error' => 'No se subió ninguna imagen'], 400);
    }

    public function eliminarImagenEvento($imagenId)
{
    $imagen = ImagenEvento::where('pk_img_evento', $imagenId)->first();

    if ($imagen) {
        Storage::disk('public')->delete($imagen->ruta);
        $imagen->delete();
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'mensaje' => 'Imagen no encontrada'], 404);
}



    public function mostrarEventoId()
{
    $eventoId = session('evento_seleccionado');
    $tipo = session('tipo_evento');

    if (!$eventoId || !$tipo) {
        return redirect()->route('lista_eventos')->with('error', 'Evento no seleccionado');
    }

    if ($tipo === 'anual') {
        $evento = EventoAnual::with('imagenes')->findOrFail($eventoId);
    } else {
        $evento = Evento::with('imagenes')->findOrFail($eventoId);
    }

    return view('inf_evento', compact('evento', 'tipo'));
}

    public function obtenerImagenesEvento(Request $request)
{
    $eventoId = session('evento_seleccionado');
    $tipo = session('tipo_evento');

    if (!$eventoId || !$tipo) {
        return response()->json(['error' => 'Evento no seleccionado'], 400);
    }

    if ($tipo === 'anual') {
        $evento = EventoAnual::with('imagenes')->findOrFail($eventoId);
    } else {
        $evento = Evento::with('imagenes')->findOrFail($eventoId);
    }

    $imagenes = $evento->imagenes->map(function ($img) {
        return [
            'name' => basename($img->ruta),
            'size' => Storage::exists($img->ruta) ? Storage::size($img->ruta) : 0,
            'url'  => Storage::url($img->ruta),
            'id'   => $img->id,
        ];
        });

        return view('editar_img_eventos', compact('evento', 'tipo'));
    }


    public function mostrar_edicion()
    {
        $pk_evento = session('evento_seleccionado');
        $tipo = session('tipo_evento');

        if (!$pk_evento  || !$tipo) {
            return redirect()->route('lista_eventos')->with('error', 'Evento no seleccionado');
        }

        if ($tipo === 'anual') {
            $evento = EventoAnual::findOrFail($pk_evento );
        } else {
            $evento = Evento::findOrFail($pk_evento );
        }

        return view('editar_eventos', [
            'evento' => $evento,
            'tipo' => $evento->tipo_fecha ? 'anual' : 'normal'
]);
    }

    public function editar(Request $request){
        $pk_evento = session('evento_seleccionado');

        if (!$pk_evento) {
            return redirect()->route('lista_eventos')->with('error', 'Evento no seleccionado.');
        }

        $evento = Evento::find($pk_evento);

        if (!$evento) {
            return redirect()->route('lista_eventos')->with('error', 'Evento no encontrado.');
        }

        $request->validate([
            'nom_evento' => 'required|string|max:255',
            'img_promocional' => 'nullable|image|mimes:jpeg,png,jpg',
            'fecha_hora' => 'required|date',
            'descripcion' => 'required|string',
            'direccion' => 'required|string'
        ],
        [
            'nom_evento.required' => 'El nombre del evento es obligatorio.',
            'img_promocional.required' => 'La imagen de portada del evento es obligatoria.',
            'img_promocional.image' => 'El archivo debe ser una imagen válida.',
            'img_promocional.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_promocional.max' => 'La imagen no debe exceder los 5MB.',
            'fecha_hora.required' => 'La fecha y hora del evento son obligatorias.',
            'fecha_hora.date' => 'La fecha y hora deben ser válidas.',
            'descripcion.required' => 'La descripción del evento es obligatoria.',
            'hora_evento.required' => 'La hora del evento es obligatoria.',
            'hora_evento.date_format' => 'La hora del evento debe estar en formato HH:MM.',
            'direccion.required' => 'La dirección del evento es obligatoria.',
        ]);

        try {
            $nombreAnterior = $evento->nom_evento;
            $slugAnterior = Str::slug($nombreAnterior);
            $slugNuevo = Str::slug($request->nom_evento);

            if ($request->hasFile('img_promocional') && $evento->img_promocional) {
                File::delete(public_path('storage/' . $evento->img_promocional));
                $evento->img_promocional = null;
            }

            if ($slugAnterior !== $slugNuevo) {
                $rutaAnterior = public_path("storage/evento_img/{$slugAnterior}");
                $rutaNueva = public_path("storage/evento_img/{$slugNuevo}");

                if (File::exists($rutaAnterior)) {
                    File::copyDirectory($rutaAnterior, $rutaNueva);
                    File::deleteDirectory($rutaAnterior);
                }

                // Actualizar las rutas de las imágenes adicionales
                ImagenEvento::where('pk_evento', $evento->pk_evento)->get()->each(function ($img) use ($slugAnterior, $slugNuevo) {
                    $rutaVieja = public_path("storage/" . $img->ruta);
                    $nuevaRuta = str_replace("evento_img/{$slugAnterior}", "evento_img/{$slugNuevo}", $img->ruta);
                    $rutaNueva = public_path("storage/" . $nuevaRuta);

                    if (File::exists($rutaVieja)) {
                        File::ensureDirectoryExists(dirname($rutaNueva));
                        File::move($rutaVieja, $rutaNueva);
                    }
                    $img->ruta = $nuevaRuta;
                    $img->save();
                });

                if ($evento->img_promocional) {
                    $nuevaRutaImgPromo = str_replace("evento_img/{$slugAnterior}", "evento_img/{$slugNuevo}", $evento->img_promocional);
                    $evento->img_promocional = $nuevaRutaImgPromo;
                }
            }


            if ($request->hasFile('img_promocional')) {
                $rutaImg = $request->file('img_promocional')->store("evento_img/{$slugNuevo}", 'public');
                $evento->img_promocional = $rutaImg;
            }


            $evento->nom_evento = $request->nom_evento;
            $evento->fecha_hora = $request->fecha_hora;
            $evento->descripcion = $request->descripcion;
            $evento->direccion = $request->direccion;
            $evento->hora_evento = $request->hora_evento;

            $evento->touch();
            $evento->save();

            LogHelper::log('info', 'Evento editado correctamente', ['evento' => $evento->nom_evento]);
            return redirect()->route('informacion_evento')->with('success', 'Evento editado correctamente.');

        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al editar el evento', [
                'evento' => $request->nom_evento,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al editar el evento: ' . $e->getMessage()]);
        }
    }
}
