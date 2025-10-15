<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario | Turismo Escuinapa</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-white text-gray-900 font-serif">

    <x-nav />

    </header>
    <!-- Sección de registro -->
    <section class="py-16 flex items-center justify-center">
        <div class="w-full max-w-xl p-8 border border-gray-400 rounded-md shadow">
            <a href="{{route('lista_usuarios')}}" onclick=""
                class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
                title="Volver">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-serif text-center mb-6">Registrar usuario nuevo</h1>

            <form action="{{ route('registrar') }}" method="POST" id="form" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Nombre:</label>
                    <input type="text" name="nombre" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium">Apellido paterno:</label>
                    <input type="text" name="apaterno" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium">Apellido materno:</label>
                    <input type="text" name="amaterno" class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium">Correo electrónico:</label>
                    <input type="email" name="email" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium">Contraseña:</label>
                    <input type="password" name="password" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium">Imagen de perfil:</label>
                    <input type="file" name="user_img" accept="image/*" class="mt-1 w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-gray-900 hover:file:bg-gray-700">
                </div>

                <div id="error-mensajes" class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6" style="display: none;">
                    <ul id="lista-errores" class="list-disc pl-5 text-sm"></ul>
                </div>

                <input type="hidden" name="fk_tipo_usuario" value="2">

                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="6Le4HDkrAAAAAEyt4yS-lPo1guwcWsn7WKuI6O7_"></div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class=" bg-gray-900 text-white py-2 px-4 rounded hover:bg-gray-700">Registrar</button>
                </div>
            </form>
            <div id="mensaje-notificacion" class="fixed top-5 right-5 z-50 hidden rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out"></div>
        </div>
    </section>
    <x-footer />
    <script>
  function mostrarMensaje(texto, tipo = 'success') {
    const div = document.getElementById('mensaje-notificacion');
    div.textContent = texto;

    const colores = {
      success: 'bg-green-600',
      error: 'bg-red-600',
      warning: 'bg-yellow-500'
    };

    div.className = `fixed top-5 right-5 z-50 rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out ${colores[tipo] || 'bg-blue-600'}`;
    div.style.opacity = '1';
    div.style.display = 'block';

    setTimeout(() => {
      div.style.opacity = '0';
    }, 3000);

    setTimeout(() => {
      div.style.display = 'none';
    }, 3500);
  }

  document.getElementById('form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const errorContainer = document.getElementById('error-mensajes');
    const errorList = document.getElementById('lista-errores');
    errorList.innerHTML = '';
    errorContainer.style.display = 'none';

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const data = await response.json();

      if (response.ok) {
        mostrarMensaje(data.mensaje || "Usuario registrado correctamente.", 'success');
        setTimeout(() => {
          window.location.href = data.redirect || "{{ route('lista_usuarios') }}";
        }, 1500);
      } else if (response.status === 422) {
        const errores = data.errors;
        for (let campo in errores) {
          errores[campo].forEach(msg => {
            const li = document.createElement('li');
            li.textContent = msg;
            errorList.appendChild(li);
          });
          errorContainer.style.display = 'block';
            mostrarMensaje("Corrige los errores del formulario.", 'warning');
        }
      } else {
        mostrarMensaje("Error al registrar usuario.", 'error');
      }
    } catch (error) {
      console.error("Error:", error);
      mostrarMensaje("Hubo un error inesperado.", 'error');
    }
  });
</script>

</body>

</html>
