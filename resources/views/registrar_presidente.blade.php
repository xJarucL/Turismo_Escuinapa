<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar presidente municipal| Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
  <x-nav />

  <div class="max-w-xl mx-auto mt-10 mb-10 bg-white p-8 rounded-lg shadow-md border border-gray-200">

    <a href="{{ route('lista_presidentes') }}" onclick="" class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow mb-4" title="Volver">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </a>
    <h1 class="text-2xl font-serif mb-6 text-center">Registrar Presidente Municipal</h1>

    <form id="form-presidente" action="{{ route('registrando_presidente') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf
      <div>
        <label class="block font-medium mb-1">Nombre completo del presidente:</label>
        <input type="text" name="nombre" placeholder="Nombre completo del presidente." required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Fecha de incio de presidencia:</label>
        <input type="date" name="fec_inicio"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Fecha de finalización de presidencia:</label>
        <input type="date" name="fec_fin"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Descripción del presidente:</label>
        <textarea rows="4" name="descripcion" required placeholder="Contexto político."
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
      </div>

      <div>
        <label class="block font-medium mb-1">Foto del presidente: </label>
        <input type="file" name="img_presidente" accept="image/*"
          class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-gray-900 hover:file:bg-gray-700">
      </div>

      <div id="error-mensajes" class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6" style="display: none;">
        <ul id="lista-errores" class="list-disc pl-5"></ul>
      </div>

      <div class="text-center pt-4">
        <button id="btn-registrar"
          class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">
          Finalizar registro
        </button>
      </div>
    </form>

    <div id="mensaje-notificacion" class="fixed top-5 right-5 z-50 hidden rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out"></div>
</div>

<x-footer />

</body>

</html>
<script>
  // Función para mostrar mensajes de notificación, de igual manera puedes modificar el diseño de acá
  function mostrarMensaje(texto, tipo = 'success') {
    const div = document.getElementById('mensaje-notificacion');
    div.textContent = texto;

    // Estilo según el tipo de alerta
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



  document.getElementById('form-presidente').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

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
        mostrarMensaje(data.mensaje || "Presidente municipal registrado exitosamente.", 'success');
        setTimeout(() => {
          window.location.href = data.redirect;
        }, 1500);
      } else if (response.status === 422) {
        const errores = data.errors;
        const listaErrores = document.getElementById('lista-errores');
        listaErrores.innerHTML = '';

        for (let campo in errores) {
          errores[campo].forEach(msg => {
            const li = document.createElement('li');
            li.textContent = msg;
            listaErrores.appendChild(li);
          });
        }

        document.getElementById('error-mensajes').style.display = 'block';
        mostrarMensaje("Corrige los errores del formulario.", 'warning');
      } else {
        mostrarMensaje("Error al registrar presidente municipal.", 'error');
      }
    } catch (error) {
      console.error("Error:", error);
      mostrarMensaje("Hubo un error inesperado.", 'error');
    }
  });
</script>
