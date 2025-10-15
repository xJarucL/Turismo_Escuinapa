<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar restaurante | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">

  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
  <x-nav />

  <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200 mb-10">
    <a href="{{route('lista_restaurantes')}}" onclick=""
      class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
      title="Volver">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </a>
    <h1 class="text-2xl font-serif mb-6 text-center">Registrar un restaurante</h1>

    <form id="form-restaurante" action="{{ route('registrando_restaurante') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
      @csrf
      <div>
        <label class="block font-medium mb-1">Nombre del restaurante:</label>
        <input type="text" name="nom_restaurante" placeholder="Nombre del restaurante" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div>
        <label class="block font-medium mb-1">Imagen promocional:</label>
        <input type="file" name="img_promocional" accept="image/*"
          class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                 file:text-white file:bg-gray-900 hover:file:bg-gray-700" />
      </div>

      <div>
        <label class="block font-medium mb-1">Dirección:</label>
        <input type="text" name="direccion" placeholder="Ubicación del restaurante" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-medium mb-1">Hora de apertura:</label>
          <input type="time" name="hora_apertura" required
            class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
          <label class="block font-medium mb-1">Hora de cierre:</label>
          <input type="time" name="hora_cierre" required
            class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
      </div>

      <div>
        <label class="block font-medium mb-1">Teléfono de contacto:</label>
        <input type="Text" name="tel_restaurante" placeholder="Ej. 6951234567" required  maxlength="10" pattern="\d{10}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div>
        <label class="block font-medium mb-1">Correo electrónico:</label>
        <input type="email" name="email_restaurante" placeholder="correo@ejemplo.com" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div>
        <label class="block font-medium mb-1">Descripción del restaurante:</label>
        <textarea name="descripcion" rows="4" placeholder="¿Qué ofrece el restaurante?" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
      </div>

      <div>
        <label class="block font-medium mb-1">URL de reseña en Google (Opcional):</label>
        <input type="url" name="url_google_resena" placeholder="https://g.page/..."
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div id="error-mensajes" class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6" style="display: none;">
      <ul id="lista-errores" class="list-disc pl-5"></ul>
    </div>

      <div class="text-center pt-4">
        <button type="submit" id="btn-registrar"
          class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">Registrar
          restaurante</button>
      </div>

    </form>

    <!-- Mensaje de notificación, acá puedes modificar el diseño, puedes moverlo donde quieras -->
<div id="mensaje-notificacion" class="fixed top-5 right-5 z-50 hidden rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out"></div>

    <!-- Dropzone-->
    <div class="mt-6" id="dropzone-container" style="display:none;">
      <label class="block font-med  ium mb-2">Imágenes adicionales</label>
      <form action="" method="POST" class="dropzone border-dashed" id="dropzoneImagenes">
        @csrf
      </form>

      <div class="text-center pt-4">
        <button id="btn-finalizar"
          class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">
          Finalizar registro
        </button>
      </div>

    </div>

  </div>

    <x-footer />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

</body>

</html>

<script>
  Dropzone.autoDiscover = false;

  // Función para mostrar mensajes de notificación
  function mostrarMensaje(texto, tipo = 'success') {
  const div = document.getElementById('mensaje-notificacion');
  div.textContent = texto;

  // Colores según el tipo
  const colores = {
    success: 'bg-green-600',
    error: 'bg-red-600',
    warning: 'bg-yellow-500'
  };

  div.className = `fixed top-5 right-5 z-50 rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out ${colores[tipo]}`;
  div.style.display = 'block';
  div.style.opacity = '1';

  setTimeout(() => {
    div.style.opacity = '0';
    setTimeout(() => div.style.display = 'none', 500);
  }, 3000);
}


  document.getElementById('form-restaurante').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'Accept': 'application/json'
        }
      });

      const data = await response.json();

      if (response.ok) {
        const restauranteId = data.restaurante_id;

        // Ocultar botón de registrar
        document.getElementById('btn-registrar').style.display = 'none';

        // Mostrar el contenedor de Dropzone
        document.getElementById('dropzone-container').style.display = 'block';

        const dropzoneForm = document.getElementById('dropzoneImagenes');
        dropzoneForm.action = `/restaurantes/${restauranteId}/imagenes`;

        // Evitar múltiples inicializaciones
        if (Dropzone.instances.length > 0) {
          Dropzone.instances.forEach(dz => dz.destroy());
        }

        const myDropzone = new Dropzone("#dropzoneImagenes", {
          url: `/restaurantes/${restauranteId}/imagenes`,
          acceptedFiles: 'image/*',
          addRemoveLinks: true,
          dictRemoveFile: "Eliminar",
          dictDefaultMessage: "Arrastra o haz clic para subir imágenes",
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function (file, response) {
              if (response.pk_img_restaurante) {
                  file.pk_img_restaurante = response.pk_img_restaurante;
                  console.log("Imagen de restaurante guardada");
              } else {
                  console.warn("No se recibió ID de imagen válido.");
              }
          },
          removedfile: function (file) {
              const imagenId = file.pk_img_restaurante;

              if (!imagenId) {
                  console.warn("No se encontró el ID de la imagen en el objeto file.");
                  return;
              }

              const url = `/restaurantes/imagenes/${imagenId}/eliminar`;

              fetch(url, {
                  method: 'DELETE',
                  headers: {
                      'X-CSRF-TOKEN': '{{ csrf_token() }}',
                      'Content-Type': 'application/json',
                  },
              })
              .then(async res => {
                  const contentType = res.headers.get("content-type");
                  if (res.ok && contentType && contentType.includes("application/json")) {
                      return res.json();
                  } else {
                      throw new Error("Respuesta no es JSON");
                  }
              })
              .then(data => {
                  if (data.success) {
                      file.previewElement.remove();
                  } else {
                      alert("Error al eliminar la imagen: " + data.mensaje);
                  }
              })
              .catch(err => {
                  console.error("Error eliminando imagen:", err);
              });
          },
          error: function (file, response) {
              console.error("Error al subir imagen:", response);
          }
      });

        document.getElementById('error-mensajes').style.display = 'none';
        mostrarMensaje("Restaurante registrado. Ahora puedes subir imágenes adicionales.");

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
        mostrarMensaje("Corrige los errores del formulario.", "warning");
      } else {
        mostrarMensaje("Error al registrar el restaurante.", "error");
      }
    } catch (error) {
      console.error("Error:", error);
      mostrarMensaje("Hubo un error inesperado.", "error");
    }
  });

  document.getElementById('btn-finalizar').addEventListener('click', function() {
    mostrarMensaje("¡Registro completado!");
    setTimeout(() => {
      window.location.href = "{{route('lista_restaurantes')}}";
    }, 1500);
  });
</script>
