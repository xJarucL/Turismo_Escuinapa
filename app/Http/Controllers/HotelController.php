<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogHelper;
use App\Models\ImagenHotel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;

class HotelController extends Controller
{

    public function registrar(Request $request)
    {
        // Validar datos
        $validator = Validator::make($request->all(), [
            'nom_hotel' => 'required|string|max:255',
            'img_hotel' => 'required|image|mimes:jpeg,png,jpg',
            'direccion' => 'required|string|max:255',
            'contacto' => ['required', 'regex:/^\d{10}$/'],
            'descripcion' => 'required|string',
            'link_hotel' => 'nullable|url',
        ],
    [
        'nom_hotel.required' => 'El nombre del hotel es obligatorio.',
        'img_hotel.required' => 'La imagen del hotel es obligatoria.',
        'img_hotel.image' => 'El archivo debe ser una imagen válida.',
        'img_hotel.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
        'img_hotel.max' => 'La imagen no debe exceder los 5MB.',
        'direccion.required' => 'La dirección es obligatoria.',
        'contacto.required' => 'El contacto es obligatorio.',
        'contacto.regex' => 'El contacto debe contener exactamente 10 dígitos numéricos, sin letras ni símbolos.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'link_hotel.url' => 'El enlace de Google Maps debe ser una URL válida.'
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
            $hotel = new Hotel();
            $hotel->nom_hotel = $request->nom_hotel;

            $slugNombreHotel = Str::slug($hotel->nom_hotel);

            if ($request->hasFile('img_hotel')) {
                $rutaImg = $request->file('img_hotel')->store("hotel_img/{$slugNombreHotel}", 'public');
                $hotel->img_hotel = $rutaImg;
            }

            $hotel->direccion = $request->direccion;
            $hotel->contacto = $request->contacto;
            $hotel->descripcion = $request->descripcion;
            $hotel->link_hotel = $request->link_hotel;
            $hotel->estatus = 1;

            $hotel->save();

            LogHelper::log('info', 'Hotel registrado exitosamente', ['Hotel' => $request->nom_hotel]);

            // Respuesta JSON con el ID del hotel (para el Dropzone)
            return response()->json(['hotel_id' => $hotel->pk_hotel]);

        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al convertir HEIC a JPG', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error al convertir imagen HEIC.',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function mostrarHoteles(){
        $hoteles = Hotel::whereNotNull('img_hotel')
                        ->where('img_hotel', '!=', '')
                         ->orderBy('updated_at', 'desc')
                        ->get();

        $hoteles_public = Hotel::where('estatus', 1) ->orderBy('updated_at', 'desc')->get();

        return view('lista_hoteles', compact('hoteles', 'hoteles_public'));
    }

    public function cambiarEstatus(Request $request, $id){
        try{
            $hotel = Hotel::findOrFail($id);
            $hotel-> estatus = $request->estatus;
            $hotel->save();

            return response()->json([
                'success' => true,
                'message' => 'Estatus actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function subirImagen(Request $request, $hotelId){
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('file')) {
            $hotel = Hotel::findOrFail($hotelId);
            $slugNombreHotel = Str::slug($hotel->nom_hotel);

            $ruta = $request->file('file')->store("hotel_img/{$slugNombreHotel}", 'public');

            $imagen = new ImagenHotel();
            $imagen->pk_hotel = $hotelId;
            $imagen->ruta = $ruta;
            $imagen->save();

            return response()->json([
                'ruta' => $ruta,
                'pk_img_hotel' => $imagen->pk_img_hotel
            ], 200);
        }

        return response()->json(['error' => 'No se ha subido ninguna imagen'], 400);
    }

    public function eliminarImagen($imagenId){
        $imagen = ImagenHotel::find($imagenId);

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

    public function mostrarHotelId(){
        $pk_hotel = session('hotel_seleccionado');

        if (!$pk_hotel) {
        return redirect()->route('lista_hoteles')->with('error', 'Hotel no seleccionado');
        }

        $hotel = Hotel::with('imagenes')->findOrFail($pk_hotel);

        return view('informacion_hotel', compact('hotel'));

    }

    public function mostrar_edicion(){
        $pk_hotel = session('hotel_seleccionado');

        if (!$pk_hotel) {
            return redirect()->route('lista_hoteles')->with('error', 'Hotel no seleccionado');
        }

        $hotel = Hotel::with('imagenes')->findOrFail($pk_hotel);

        return view('editar_hoteles', compact('hotel'));
    }

    public function editar(Request $request){
        $pk_hotel = session('hotel_seleccionado');

        if (!$pk_hotel) {
            return redirect()->route('lista_hoteles')->with('error', 'Hotel no seleccionado.');
        }

        $hotel = Hotel::find($pk_hotel);

        if (!$hotel) {
            return redirect()->route('lista_hoteles')->with('error', 'Hotel no encontrado.');
        }

        $request->validate([
            'nom_hotel' => 'required|string|max:255',
            'img_hotel' => 'nullable|image|mimes:jpeg,png,jpg',
            'direccion' => 'required|string|max:255',
            'contacto' => ['required', 'regex:/^\d{10}$/'],
            'descripcion' => 'required|string',
            'link_hotel' => 'nullable|url'
        ],
        [
            'nom_hotel.required' => 'El nombre del hotel es obligatorio.',
            'img_hotel.image' => 'El archivo debe ser una imagen válida.',
            'img_hotel.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_hotel.max' => 'La imagen no debe exceder los 2MB.',
            'direccion.required' => 'La dirección del hotel es obligatoria.',
            'contacto.required' => 'El contacto del hotel es obligatorio.',
            'contacto.regex' => 'El contacto debe contener exactamente 10 dígitos numéricos, sin letras ni símbolos.',
            'descripcion.required' => 'La descri pción del hotel es obligatoria.',
            'link_hotel.url' => 'El enlace del hotel debe ser una URL válida.',
        ]);

        try {
            $nombreAnterior = $hotel->nom_hotel;
            $slugAnterior = Str::slug($nombreAnterior);
            $slugNuevo = Str::slug($request->nom_hotel);

            // Si se subió una nueva imagen, eliminar la anterior
            if ($request->hasFile('img_hotel') && $hotel->img_hotel) {
                File::delete(public_path('storage/' . $hotel->img_hotel));
                $hotel->img_hotel = null;
            }

            // Renombrar carpeta si el slug cambió
            if ($slugAnterior !== $slugNuevo) {
                $rutaAnterior = public_path("storage/hotel_img/{$slugAnterior}");
                $rutaNueva = public_path("storage/hotel_img/{$slugNuevo}");

                if (File::exists($rutaAnterior)) {
                    File::copyDirectory($rutaAnterior, $rutaNueva);
                    File::deleteDirectory($rutaAnterior);
                }

                ImagenHotel::where('pk_hotel', $hotel->pk_hotel)->get()->each(function ($img) use ($slugAnterior, $slugNuevo) {
                    $rutaVieja = public_path("storage/" . $img->ruta);
                    $nuevaRuta = str_replace("hotel_img/{$slugAnterior}", "hotel_img/{$slugNuevo}", $img->ruta);
                    $rutaNueva = public_path("storage/" . $nuevaRuta);

                    if (File::exists($rutaVieja)) {
                        File::ensureDirectoryExists(dirname($rutaNueva));
                        File::move($rutaVieja, $rutaNueva);
                    }

                    $img->ruta = $nuevaRuta;
                    $img->save();
                });

                if ($hotel->img_hotel) {
                    $nuevaRutaImg = str_replace("hotel_img/{$slugAnterior}", "hotel_img/{$slugNuevo}", $hotel->img_hotel);
                    $hotel->img_hotel = $nuevaRutaImg;
                }
            }

            // Guardar nueva imagen si se subió
            if ($request->hasFile('img_hotel')) {
                // Eliminar imagen anterior si existe
                if ($hotel->img_hotel && Storage::disk('public')->exists($hotel->img_hotel)) {
                    Storage::disk('public')->delete($hotel->img_hotel);
                }

                $rutaImagen = $request->file('img_hotel')->store("hotel_img/{$slugNuevo}", 'public');
                $hotel->img_hotel = $rutaImagen;
            }

            $hotel->nom_hotel = $request->nom_hotel;
            $hotel->direccion = $request->direccion;
            $hotel->contacto = $request->contacto;
            $hotel->descripcion = $request->descripcion;
            $hotel->link_hotel = $request->link_hotel;

            $hotel->touch();
            $hotel->save();

            LogHelper::log('info', 'Hotel editado correctamente', ['hotel' => $hotel->nom_hotel]);
            return redirect()->route('informacion_hotel')->with('success', 'Hotel editado correctamente.');

        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al editar el hotel', [
                'hotel' => $request->nom_hotel,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al editar el hotel: ' . $e->getMessage()]);
        }
    }

public function obtenerImagenesHotel(Request $request)
{
    $hotelId = session('hotel_seleccionado');

    if (!$hotelId) {
        return response()->json(['error' => 'Hotel no seleccionado'], 400);
    }

    $hotel = Hotel::with('imagenes')->findOrFail($hotelId);

    $imagenes = $hotel->imagenes->map(function ($img) {
        return [
            'name' => basename($img->ruta),
            'size' => Storage::exists($img->ruta) ? Storage::size($img->ruta) : 0,
            'url'  => Storage::url($img->ruta),
            'id'   => $img->pk_img_hotel,
        ];
    });

    return view('editar_img_hoteles', compact('hotel'));
}



}
