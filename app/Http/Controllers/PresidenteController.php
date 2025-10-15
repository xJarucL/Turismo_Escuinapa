<?php

namespace App\Http\Controllers;

use App\Models\Presidente;
use Illuminate\Http\Request;
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

class PresidenteController extends Controller
{
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fec_inicio' => 'required|date',
            'fec_fin' => 'required|date',
            'img_presidente' => 'required|image|mimes:jpeg,png,jpg',
        ],
        [
            'nombre.required' => 'El nombre del presidente es obligatorio.',
            'img_presidente.required' => 'La imagen del presidente es obligatoria.',
            'img_presidente.image' => 'El archivo debe ser una imagen válida.',
            'img_presidente.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_presidente.max' => 'La imagen no debe exceder los 5MB.',
            'descripcion.required' => 'La descripcion es obligatoria.',
            'fec_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fec_fin.required' => 'La fecha de finalización es obligatoria.',
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
            $presidente = new Presidente();
            $presidente->nombre = $request->nombre;

            $slugNombrePresidente = Str::slug($presidente->nombre);

            if ($request->hasFile('img_presidente')) {
                $rutaImg = $request->file('img_presidente')->store("presidentes/{$slugNombrePresidente}", 'public');
                $presidente->img_presidente = $rutaImg;
            }

            $presidente->descripcion = $request->descripcion;
            $presidente->fec_inicio = $request->fec_inicio;
            $presidente->fec_fin = $request->fec_fin;
            $presidente->estatus = 1;

            $presidente->save();

            LogHelper::log('info', 'Presidente registrado exitosamente', ['Presidente' => $request->nombre]);

            return response()->json([
                'mensaje' => 'Presidente registrado exitosamente.',
                'redirect' => route('lista_presidentes')
            ]);

        } catch (\Exception $e) {
            Log::error('Error al registrar presidente municipal: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Error al registrar presidente municipal.'], 500);
            } else {
                return redirect()->back()->with('error', 'Error al registrar presidente.');
            }
        }
    }

    public function mostrarPresidentes(){
        $presidentes = Presidente::whereNotNull('img_presidente')
                    ->where('img_presidente', '!=', '')
                    ->orderBy('fec_inicio', 'desc')
                    ->get();

        $presidentes_public = Presidente::where('estatus', 1)
                        ->orderBy('fec_inicio', 'desc')
                        ->get();
        return view('lista_presidentes', compact('presidentes', 'presidentes_public'));
    }

    public function cambiarEstatus($id, Request $request){
        try {
            $presidente = Presidente::findOrFail($id);
            $presidente->estatus = $request->estatus;
            $presidente->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function mostrarPresidenteId(){
        $pk_presidente = session('presidente_seleccionado');

        if (!$pk_presidente) {
            return redirect()->route('lista_presidentes')->with('error', 'Presidente no seleccionado');
        }

        $presidente = Presidente::findOrFail($pk_presidente);

        return view('inf_presidente', compact('presidente'));
    }

    public function mostrar_edicion(){
        $pk_presidente = session('presidente_seleccionado');

        if (!$pk_presidente) {
            return redirect()->route('lista_presidentes')->with('error', 'Presidente no seleccionado');
        }

        $presidente = Presidente::findOrFail($pk_presidente);

        return view('editar_presidente', compact('presidente'));
    }

    public function editar(Request $request){
        $pk_presidente = session('presidente_seleccionado');

        if (!$pk_presidente) {
            return redirect()->route('lista_presidentes')->with('error', 'Presidente no seleccionado.');
        }

        $presidente = Presidente::find($pk_presidente);

        if (!$presidente) {
            return redirect()->route('lista_presidentes')->with('error', 'Presidente no encontrado.');
        }

        $request->validate([

            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fec_inicio' => 'required|date',
            'fec_fin' => 'required|date',
            'img_presidente' => 'nullable|image|mimes:jpeg,png,jpg',
        ],
        [
            'nombre.required' => 'El nombre del presidente es obligatorio.',
            'img_presidente.required' => 'La imagen del presidente es obligatoria.',
            'img_presidente.image' => 'El archivo debe ser una imagen válida.',
            'img_presidente.mimes' => 'La imagen debe ser de tipo jpeg, png o jpg.',
            'img_presidente.max' => 'La imagen no debe exceder los 5MB.',
            'descripcion.required' => 'La descripcion es obligatoria.',
            'fec_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fec_fin.required' => 'La fecha de finalización es obligatoria.',
        ]);

        try {
            $nombreAnterior = $presidente->nombre;
            $slugAnterior = Str::slug($nombreAnterior);
            $slugNuevo = Str::slug($request->nombre);

            // Si se subió una nueva imagen, eliminar la anterior
            if ($request->hasFile('img_presidente') && $presidente->img_presidente) {
                File::delete(public_path('storage/' . $presidente->img_presidente));
                $presidente->img_presidente = null;
            }

            // Renombrar carpeta si el slug cambió
            if ($slugAnterior !== $slugNuevo) {
                $rutaAnterior = public_path("storage/presidente_img/{$slugAnterior}");
                $rutaNueva = public_path("storage/presidente_img/{$slugNuevo}");

                if (File::exists($rutaAnterior)) {
                    File::copyDirectory($rutaAnterior, $rutaNueva);
                    File::deleteDirectory($rutaAnterior);
                }

                if ($presidente->img_presidente) {
                    $nuevaRutaImg = str_replace("presidente_img/{$slugAnterior}", "presidente_img/{$slugNuevo}", $presidente->img_presidente);

                    $rutaVieja = public_path("storage/" . $presidente->img_presidente);
                    $rutaNuevaCompleta = public_path("storage/" . $nuevaRutaImg);

                    if (File::exists($rutaVieja)) {
                        File::ensureDirectoryExists(dirname($rutaNuevaCompleta));
                        File::move($rutaVieja, $rutaNuevaCompleta);
                    }

                    $presidente->img_presidente = $nuevaRutaImg;
                }
            }

            // Guardar nueva imagen si se subió
            if ($request->hasFile('img_presidente')) {
                // Eliminar imagen anterior en disco si existe
                if ($presidente->img_presidente && Storage::disk('public')->exists($presidente->img_presidente)) {
                    Storage::disk('public')->delete($presidente->img_presidente);
                }

                $rutaImagen = $request->file('img_presidente')->store("presidente_img/{$slugNuevo}", 'public');
                $presidente->img_presidente = $rutaImagen;
            }

            $presidente->nombre = $request->nombre;
            $presidente->descripcion = $request->descripcion;
            $presidente->fec_inicio = $request->fec_inicio;
            $presidente->fec_fin = $request->fec_fin;

            $presidente->touch();
            $presidente->save();

            LogHelper::log('info', 'Presidente editado correctamente', ['presidente' => $presidente->nombre]);
            return redirect()->route('informacion_presidente')->with('success', 'Presidente editado correctamente.');

        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al editar el presidente', [
                'presidente' => $request->nombre,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Error al editar el presidente: ' . $e->getMessage()]);
        }
    }
}
