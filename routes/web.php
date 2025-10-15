<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PresidenteController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventoAnualController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\LugarInteresController;
use App\Http\Controllers\ComidaTipicaController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Middleware\RoleMiddleware;
use App\Models\Restaurante;
use Illuminate\Http\Request;

Route::get('/',  [UsuarioController::class, 'mostrarTituloPanel'])->name('index');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/panel-administrador');
    }
    return view('login');
})->name('login');

// Ruta para iniciar sesion
Route::post('/iniciar', [UsuarioController::class, 'login'])->name('login.inicia');
// Ruta para cerrar sesion
Route::get('/logout', [UsuarioController::class, 'logout'])->name('logout');
// Recuperar contraseña
Route::get('/recuperar_contraseña', function () {
    return view('recuperar_contraseña');
})->name('recuperar_contraseña');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// EVENTOS - PÚBLICO
Route::get('/lista-eventos', [EventoController::class, 'mostrarEventos'])->name('lista_eventos');
Route::get('/inf-evento', [EventoController::class, 'mostrarEventoId'])->name('informacion_evento');
Route::post('/evento/seleccionar', function (Request $request) {session(['evento_seleccionado' => $request->pk_evento,'tipo_evento' => $request->tipo]);return response()->json(['ok' => true]);})->name('evento.seleccionar');

// CALENDARIO
Route::get('/calendario-eventos', [EventoController::class, 'mostrarCalendario'])->name('calendario_eventos');
Route::get('/api/eventos/{anio}/{mes}', [EventoController::class, 'getEventosPorMes'])->name('api.eventos');

// HOTELES - PÚBLICO
Route::get('/inf-hotel', [HotelController:: class, 'mostrarHotelId'])->name('informacion_hotel');
Route::get('/lista-hoteles', [HotelController::class, 'mostrarHoteles'])->name('lista_hoteles');
Route::get('/hotel/buscar', [HotelController::class, 'buscar']);
Route::post('/hotel/seleccionar', function (Request $request) {session(['hotel_seleccionado' => $request->pk_hotel]);return response()->json(['ok' => true]);})->name('hotel.seleccionar');

// RESTAURANTES - PÚBLICO
Route::get('/lista-restaurante', [RestauranteController::class, 'mostrarRestaurantes'])->name('lista_restaurantes');
Route::get('/inf-restaurante', [RestauranteController::class, 'mostrarRestauranteId'])->name('informacion_restaurante');
Route::get('/restaurante/buscar', [RestauranteController::class, 'buscar']);
Route::post('/restaurante/seleccionar', function (Request $request) {session(['restaurante_seleccionado' => $request->pk_restaurante]);return response()->json(['ok' => true]);})->name('restaurante.seleccionar');

// COMIDAS TÍPICAS - PÚBLICO
Route::get('/lista-comidas-tipicas', [ComidaTipicaController::class, 'mostrarComidas'])->name('lista_comidas_tipicas');
Route::get('/inf-comida', [ComidaTipicaController::class, 'mostrarComidaId'])->name('informacion_comida');
Route::post('/comida/seleccionar', function (Request $request) {session(['comida_seleccionada' => $request->pk_comida_tipica]);return response()->json(['ok' => true]);})->name('comida.seleccionar');

// PRESIDENTES - PÚBLICO
Route::get('/lista-presidentes', [PresidenteController::class, 'mostrarPresidentes'])->name('lista_presidentes');
Route::post('/presidente/seleccionar', function (Request $request) {session(['presidente_seleccionado' => $request->pk_presidente]);return response()->json(['ok' => true]);})->name('presidente.seleccionar');
Route::get('/inf-presidente', [PresidenteController::class, 'mostrarPresidenteId'])->name('informacion_presidente');

// LUGARES DE INTERES - PÚBLICO
Route::get('/lista-lugares', [LugarInteresController::class, 'mostrarLugares'])->name('lista_lugares');
Route::get('/inf-lugar', [LugarInteresController::class, 'mostrarLugarId'])->name('informacion_lugar');
Route::post('/lugar/seleccionar', function (Request $request) {session(['lugar_seleccionado' => $request->pk_lugar_interes]);return response()->json(['ok' => true]);})->name('lugar.seleccionar');


// Middleware para verificar si el usuario está autenticado
Route::middleware(['auth'])->group(function () {

    // USUARIOS
    Route::get('/panel-ayuntamiento', function () {
        return view('panel_ayuntamiento');
    })->name('ayuntamiento');
    Route::get('/informacion-usuario', [UsuarioController::class, 'mostrar_usuario'])->name('informacion_usuario');
    Route::get('/editar-usuario', [UsuarioController::class, 'mostrar_edicion'])->name('editar_usuario');
    Route::put('/actualizar-usuario', [UsuarioController::class, 'editar'])->name('actualizar_usuario');
    Route::post('/actualizar-password', [UsuarioController::class, 'actualizarPassword'])->name('actualizar.password');


    //  EVENTOS
    Route::get('/registro-evento', function () { return view('registrar_evento'); })->name('registro_evento');
    Route::post('/registrando_evento', [EventoController::class, 'registrar'])->name('registrando_evento');
    Route::post('/registrando_eventoAnual', [EventoAnualController::class, 'registrar'])->name('registrando_evento_anual');
    Route::put('/editando-evento', [EventoController::class, 'editar'])->name('editando_evento');
    Route::put('/editando_evento_anual', [EventoAnualController::class, 'editar'])->name('editando_evento_anual');
    Route::get('/editar-evento', [EventoController::class, 'mostrar_edicion'])->name('editar_eventos');
    Route::post('/evento/cambiar-estatus/{id}', [EventoController::class, 'cambiarEstatus']);
    Route::post('/evento-anual/cambiar-estatus/{id}', [EventoAnualController::class, 'cambiarEstatus']);
    Route::post('/eventos/{eventoId}/imagenes', [EventoController::class, 'subirImagen'])->name('eventos.imagenes.subir');
    Route::delete('/eventos/imagenes/{imagenId}/eliminar', [EventoController::class, 'eliminarImagenEvento'])->name('eventos.imagenes.eliminar');
    Route::post('/eventos-anuales/{eventoId}/imagenes', [EventoAnualController::class, 'subirImagen'])->name('eventos.anuales.imagenes.subir');
    Route::delete('/eventos-anuales/imagenes/{imagenId}/eliminar', [EventoAnualController::class, 'eliminarImagenEvento'])->name('eventos.anuales.imagenes.eliminar');
    Route::get('/eventos/imagenes/cargar', [EventoController::class, 'obtenerImagenesEvento'])->name('eventos.imagenes.cargar');


    // HOTELES
    Route::get('/registro-hotel', function () { return view('registrar_hotel'); })->name('registro_hotel');
    Route::post('/registrando_hotel', [HotelController::class, 'registrar'])->name('registrando_hotel');
    Route::put('/editando-hotel', [HotelController::class, 'editar'])->name('editando_hotel');
    Route::get('/editar-hotel', [HotelController::class, 'mostrar_edicion'])->name('editar_hoteles');
    Route::post('/hotel/cambiar-estatus/{id}', [HotelController::class, 'cambiarEstatus']);
    Route::post('/hoteles/{hotelId}/imagenes', [HotelController::class, 'subirImagen'])->name('hoteles.imagenes.subir');
    Route::delete('/hoteles/imagenes/{imagenId}/eliminar', [HotelController::class, 'eliminarImagen'])->name('hoteles.imagenes.eliminar');
    Route::get('/hoteles/imagenes/cargar', [HotelController::class, 'obtenerImagenesHotel'])->name('hoteles.imagenes.cargar');


    // RESTAURANTES
    Route::get('/registro-restaurante', function () { return view('registrar_restaurante'); })->name('registro_restaurante');
    Route::post('/registrando_restaurante', [RestauranteController::class, 'registrar'])->name('registrando_restaurante');
    Route::get('/editar-restaurante', [RestauranteController::class, 'mostrar_edicion'])->name('editar_restaurantes');
    Route::put('/editando-restaurante', [RestauranteController::class, 'editar'])->name('editando_restaurante');
    Route::post('/restaurante/cambiar-estatus/{id}', [RestauranteController::class, 'cambiarEstatus']);
    Route::post('/restaurantes/{restauranteId}/imagenes', [RestauranteController::class, 'subirImagen'])->name('restaurantes.imagenes.subir');
    Route::delete('/restaurantes/imagenes/{imagenId}/eliminar', [RestauranteController::class, 'eliminarImagen'])->name('restaurantes.imagenes.eliminar');
    Route::get('/restaurantes/imagenes/cargar', [RestauranteController::class, 'obtenerImagenesRestaurante'])->name('restaurantes.imagenes.cargar');


    // COMIDAS TÍPICAS
    Route::get('/registro-comida-tipica', [ComidaTipicaController::class, 'cargarRegistro'])->name('registro_comida_tipica');
    Route::post('/registrando-comida', [ComidaTipicaController::class, 'registrar'])->name('registrando-comida');
    Route::post('/comidas/{comidaId}/imagenes', [ComidaTipicaController::class, 'subirImagen'])->name('comidas.imagenes.subir');
    Route::delete('/comidas/imagenes/{imagenId}/eliminar', [ComidaTipicaController::class, 'eliminarImagen'])->name('comidas.imagenes.eliminar');
    Route::post('/comida/cambiar-estatus/{id}', [ComidaTipicaController::class, 'cambiarEstatus']);
    Route::get('/editar-comida', [ComidaTipicaController::class, 'mostrar_edicion'])->name('editar_comida_tipica');
    Route::put('/editando-comida', [ComidaTipicaController::class, 'editar'])->name('editando_comida_tipica');
    Route::get('/comidas/imagenes/cargar', [ComidaTipicaController::class, 'obtenerImagenesComida'])->name('comidas.imagenes.cargar');


    // PRESIDENTES
    Route::get('/registro-presidente', function () { return view('registrar_presidente'); })->name('registro_presidente');
    Route::post('/registrando-presidente', [PresidenteController::class, 'registrar'])->name('registrando_presidente');
    Route::post('/presidente/cambiar-estatus/{id}', [PresidenteController::class, 'cambiarEstatus']);
    Route::get('/editar-presidente', [PresidenteController::class, 'mostrar_edicion'])->name('editar_presidente');
    Route::put('/editando-presidente', [PresidenteController::class, 'editar'])->name('editando_presidente');


    // LUGARES DE INTERÉS
    Route::get('/registro-lugar-interes', function () { return view('registrar_lugar_interes'); })->name('registro_lugar_interes');
    Route::post('/registrando_lugar_interes', [LugarInteresController::class, 'registrar'])->name('registrando_lugar_interes');
    Route::post('/lugares/{lugarInteresId}/imagenes', [LugarInteresController::class, 'subirImagen'])->name('lugares.imagenes.subir');
    Route::delete('/lugares/imagenes/{imagenId}/eliminar', [LugarInteresController::class, 'eliminarImagen'])->name('lugares.imagenes.eliminar');
    Route::get('/lugares/imagenes/cargar', [LugarInteresController::class, 'obtenerImagenesLugar'])->name('lugares.imagenes.cargar');
    Route::post('/lugar/cambiar-estatus/{id}', [LugarInteresController::class, 'cambiarEstatus']);
    Route::get('/editar-lugar', [LugarInteresController::class, 'mostrar_edicion'])->name('editar_lugar');
    Route::put('/editando-lugar', [LugarInteresController::class, 'editar'])->name('editando_lugar');


    // AVISO DE PRIVACIDAD
    Route::get('/aviso-priv', function () {return view('aviso-priv');})->name('aviso-priv');


    // ADMINISTRADOR
    Route::middleware([RoleMiddleware::class])->group(function () {

        Route::get('/panel-administrador', function () {
            return view('panel_administrador');
        })->name('administrador');
        Route::post('/registrando_usuario', [UsuarioController::class, 'registrar'])->name('registrar');
        Route::get('/lista_usuarios', [UsuarioController::class, 'mostrar_usuarios'])->name('lista_usuarios');
        Route::put('/actualizar-rol', [UsuarioController::class, 'actualizarRol']);
        Route::put('/actualizar-estatus', [UsuarioController::class, 'actualizarEstatus']);
        Route::get('/panel-administrador/registro-usuario', function () {return view('registrar_usuario');})->name('registrar_usuario');
        Route::get('/panel-administrador/editar-panel', [UsuarioController::class, 'mostrarEdicionTituloPanel'])->name('editar_panel');
        Route::put('/actualizar-titulo-panel', [UsuarioController::class, 'actualizarTituloPanel'])->name('editando_panel');
    });

});


Route::get('/historia', function () {return view('historia');})->name('historia');
