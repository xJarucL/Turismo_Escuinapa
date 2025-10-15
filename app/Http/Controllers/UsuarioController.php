<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\File;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\Validator;
use App\Models\TituloPanel;

class UsuarioController extends Controller
{
    // Funcion de registro de usuario
    public function registrar(Request $request)
    {
        LogHelper::log('info', 'Intento de registro', ['email' => $request->email]);

        $validator = Validator::make($request->all(),[
            'g-recaptcha-response' => 'required',
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'user_img' => 'image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'g-recaptcha-response.required' => 'Debes completar el CAPTCHA.',
            'nombre.required' => 'El nombre es obligatorio.',
            'apaterno.required' => 'El apellido paterno es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena válida.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'user_img.image' => 'El archivo debe ser una imagen.',
            'user_img.mimes' => 'La imagen debe estar en formato JPEG, PNG o JPG.',
            'user_img.max' => 'La imagen no debe superar los 5MB.',
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

        $secret = "6Le4HDkrAAAAABjTANJIdSofXstBJqKNgPyjnYwD";
        $response = $request->input('g-recaptcha-response');

        $captchaValidation = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => $secret,
            'response' => $response,
        ]);

        $captchaResult = $captchaValidation->json();

        if (!$captchaResult['success']) {
            LogHelper::log('error', 'Error en el CAPTCHA', ['email' => $request->email]);

            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['g-recaptcha-response' => ['Error en el CAPTCHA. Inténtalo de nuevo.']]
                ], 422);
            }else{
                 return redirect()->back()->withErrors(['g-recaptcha-response' => 'Error en el CAPTCHA. Inténtalo de nuevo.'])->withInput();
            }
        }

        try {
            $user = new User();
            $user->nombre = $request->nombre;
            $user->apaterno = $request->apaterno;
            $user->amaterno = $request->amaterno;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            if ($request->hasFile('user_img')) {
                $rutaImg = $request->file('user_img')->store('imagen_user', 'public');
                $user->user_img = $rutaImg;
            }

            $user->fk_tipo_usuario = $request->fk_tipo_usuario;
            $user->remember_token = Hash::make($request->email);
            $user->estatus = 1;
            $user->save();

            LogHelper::log('info', 'Usuario registrado con éxito', ['email' => $request->email]);

            if ($request->ajax()) {
                return response()->json([
                    'mensaje' => 'Usuario registrado correctamente.',
                    'redirect' => route('lista_usuarios')
                ]);
            }

            return redirect()->route('lista_usuarios')->with('success', 'Usuario registrado correctamente.');
        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al registrar el usuario', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Error al registrar usuario.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al registrar usuario.');
        }
    }


    // Funcion de inicio de sesion
    public function login(Request $request)
    {
        // Aplicar los logs despues de la implementacion del inicio de sesion
        LogHelper::log('info', 'Intento de inicio de sesión', ['email' => $request->email]);
        // Validacion del usuario, contraseña y CAPTCHA
        // Validacion de los datos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ], [
            'email.required' => 'El correo es requerido.',
            'email.email' => 'El correo no es valido.',
            'password.required' => 'La contraseña es requerida.',
            'g-recaptcha-response.required' => 'Debes completar el CAPTCHA.'
        ]);

        // Llave del CAPTCHA
        $secret = "6Le4HDkrAAAAABjTANJIdSofXstBJqKNgPyjnYwD";
        $response = $request->input('g-recaptcha-response');

        $captchaValidation = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => $secret,
            'response' => $response,
        ]);

        $captchaResult = $captchaValidation->json();

        // Verificacion del CAPTCHA
        if (!$captchaResult['success']) {
            LogHelper::log('error', 'Error en el CAPTCHA', ['email' => $request->correo]);
            return back()->withErrors(['g-recaptcha-response' => 'Error en el CAPTCHA. Inténtalo de nuevo.']);
        }

        // Verificacion de los datos del formulario
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'estatus' => 1
        ];
        // Enrutamiento del usuario dependiendo del tipo de usuario
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Registrar usuario de session
            $request->session()->put('user_id', Auth::id());
            LogHelper::log('info', 'Inicio de sesión exitoso', ['email' => $request->email]);

            $rol = Auth::user()->fk_tipo_usuario;
            if ($rol == 1) {
                return redirect()->route('administrador');
            } elseif ($rol == 2) {
                return redirect()->route('ayuntamiento');
            } else {
                return redirect()->route('login');
            }
        } else {
            LogHelper::log('error', 'Intento de sesion fallido', ['email' => $request->correo]);
            return back()->withErrors(['email' => 'Las credenciales no son correctas.']);
        }
    }

    // Funcion de cierre de sesion
    public function logout(Request $request)
    {
        // Obtener el correo del usuario autenticado
        $email = Auth::user() ? Auth::user()->email : 'Usuario no autenticado';
        // Aplicar los logs despues de la implementacion del cierre de sesion
        LogHelper::log('info', 'Cierre de sesión', ['email' => $email]);
        // Cerrar la sesion
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redireccionar a la vista de inicio de sesion
        return redirect()->route('index')->with('success', 'Sesión cerrada correctamente.');
    }

    // Función de mostrar la vista de informacion del usuario
    public function mostrar_usuario()
    {
        $pk_usuario = session('user_id');
        if (!$pk_usuario) {
            return redirect()->route('login')->withErrors(['usuario' => 'Sesión inválida']);
        }

        $usuario = User::find($pk_usuario);
        if (!$usuario) {
            return redirect()->route('login')->withErrors(['usuario' => 'Usuario no encontrado']);
        }
        return view('informacion_usuario', compact('usuario'));
    }

    // Función para mostrar la vista de edición del usuario
    public function mostrar_edicion()
    {
        $pk_usuario = session('user_id');
        if (!$pk_usuario) {
            return redirect()->route('login')->withErrors(['usuario' => 'Sesión inválida']);
        }

        $usuario = User::find($pk_usuario);
        if (!$usuario) {
            return redirect()->route('login')->withErrors(['usuario' => 'Usuario no encontrado']);
        }
        return view('editar_usuario', compact('usuario'));
    }

    // Funcion de editar el usuario
    public function editar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apaterno' => 'required|string|max:255',
            'amaterno' => 'nullable|string|max:255',
            'password' => 'nullable|string|confirmed|min:6',
            'user_img' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'g-recaptcha-response' => 'required'
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apaterno.required' => 'El apellido paterno es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'g-recaptcha-response.required' => 'Debes completar el CAPTCHA.'
        ]);

        // Validar el CAPTCHA
        $secret = "6Le4HDkrAAAAABjTANJIdSofXstBJqKNgPyjnYwD";
        $response = $request->input('g-recaptcha-response');
        $captchaValidation = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => $secret,
            'response' => $response,
        ]);
        $captchaResult = $captchaValidation->json();

        if (!$captchaResult['success']) {
            LogHelper::log('error', 'Error en el CAPTCHA', ['email' => $request->email]);
            return back()->withErrors(['g-recaptcha-response' => 'Error en el CAPTCHA. Inténtalo de nuevo.']);
        }

        $usuario = User::find(session('user_id'));
        if (!$usuario) {
            return redirect()->route('login')->withErrors(['usuario' => 'Usuario no encontrado']);
        }

        $usuario->nombre = $request->nombre;
        $usuario->apaterno = $request->apaterno;
        $usuario->amaterno = $request->amaterno;

        if ($request->hasFile('user_img')) {

            $foto_anterior = $usuario->user_img;
            if ($foto_anterior) {
                $ruta_foto_anterior = public_path('storage/' . $foto_anterior);
                if (File::exists($ruta_foto_anterior)) {
                    File::delete($ruta_foto_anterior);
                }
            }
            // Guardar la nueva imagen
            $rutaImg = $request->file('user_img')->store('imagen_user', 'public');
            $usuario->user_img = $rutaImg;
        }

        try {
            $usuario->save();
            LogHelper::log('info', 'Usuario editado exitosamente', ['email' => $request->email]);
            return redirect()->route('informacion_usuario')->with('success', 'Usuario editado correctamente.');
        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al editar el usuario', ['email' => $request->email, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function actualizarPassword(Request $request)
    {
        $request->validate([
            'password_anterior' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
            'g-recaptcha-response' => 'required'
        ], [
            'password_anterior.required' => 'Debes ingresar tu contraseña actual.',
            'password.required' => 'Debes ingresar una nueva contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'g-recaptcha-response.required' => 'Debes completar el CAPTCHA.'
        ]);

        // Validar el CAPTCHA
        $secret = "6Le4HDkrAAAAABjTANJIdSofXstBJqKNgPyjnYwD";
        $response = $request->input('g-recaptcha-response');
        $captchaValidation = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => $secret,
            'response' => $response,
        ]);
        $captchaResult = $captchaValidation->json();

        if (!$captchaResult['success']) {
            LogHelper::log('error', 'Error en el CAPTCHA', []);
            return back()->withErrors(['g-recaptcha-response' => 'Error en el CAPTCHA. Inténtalo de nuevo.']);
        }

        $usuario = User::find(session('user_id'));
        if (!$usuario) {
            return redirect()->route('login')->withErrors(['usuario' => 'Usuario no encontrado.']);
        }

        if (!Hash::check($request->password_anterior, $usuario->password)) {
            return back()->withErrors(['password_anterior' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar contraseña
        $usuario->password = Hash::make($request->password);

        try {
            $usuario->save();
            LogHelper::log('info', 'Contraseña actualizada exitosamente', ['user_id' => $usuario->id]);
            return redirect()->route('informacion_usuario')->with('success', 'Contraseña actualizada correctamente.');
        } catch (\Exception $e) {
            LogHelper::log('error', 'Error al cambiar contraseña', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al cambiar la contraseña.']);
        }
    }

    public function mostrar_usuarios()
    {
        $usuarios = User::paginate(10);
        return view('lista_usuarios', compact('usuarios'));
    }


    public function actualizarRol(Request $request)
    {
        $usuario = User::find($request->pk_usuario);
        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        $usuario->fk_tipo_usuario = $request->fk_tipo_usuario;
        $usuario->save();

        return response()->json(['success' => true, 'message' => 'Rol actualizado correctamente']);
    }

    public function actualizarEstatus(Request $request)
    {
        $usuario = User::find($request->pk_usuario);
        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado']);
        }

        $usuario->estatus = $request->estatus;
        $usuario->save();

        $estado = $usuario->estatus ? 'habilitado' : 'deshabilitado';
        return response()->json(['success' => true, 'message' => "Usuario $estado correctamente"]);
    }

    public function mostrarTituloPanel()
    {
        $tituloPanel = TituloPanel::first();
        return view('index', compact('tituloPanel'));
    }

    // Función para mostrar la vista de edición del título y subtítulo del panel
    public function mostrarEdicionTituloPanel()
    {
        $tituloPanel = TituloPanel::first();
        if (!$tituloPanel) {
            return redirect()->route('administrador')->withErrors(['error' => 'Título y subtítulo no encontrados.']);
        }
        return view('editar_baner', compact('tituloPanel'));
    }

    public function actualizarTituloPanel(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string|max:255',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'subtitulo.required' => 'El subtítulo es obligatorio.',
        ]);

        $tituloPanel = TituloPanel::first();
        if (!$tituloPanel) {
            return redirect()->route('administrador')->withErrors(['error' => 'Título y subtítulo no encontrados.']);
        }
        $tituloPanel->titulo = $request->titulo;
        $tituloPanel->subtitulo = $request->subtitulo;
        $tituloPanel->save();

        return redirect()->route('administrador')->with('success', 'Título y subtítulo actualizados correctamente.');
    }
}
