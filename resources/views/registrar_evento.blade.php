<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrar evento | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">

  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
  <x-nav />

  <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200 mb-10">
    <a href="{{route('lista_eventos')}}" onclick=""
      class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
      title="Volver">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </a>
    <h1 class="text-2xl font-serif mb-6 text-center">Registrar Evento</h1>


    <label for="switch-eventoAnual" class="inline-flex items-center me-5 cursor-pointer">
      <input type="checkbox" id="switch-eventoAnual" class="sr-only peer">
      <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus700ring-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-700-600 peer-checked:bg-green-600 dark:peer-700d:bg-green-600 dot"></div>
      <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-500">Evento anual</span>
    </label>
    <p class="font-serif col-span-3 text-gray-500 mt-10">Si no es un evento anual no selccione la casilla.</p>

    <form id="form-evento" action="{{ route('registrando_evento') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
      @csrf
      <input type="hidden" name="es_anual" id="input-es-anual" value="false">
      <div>
        <label class="block font-medium mb-1">Nombre del evento</label>
        <input type="text" name="nom_evento" placeholder="Nombre del evento" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div id="campo-fecha-normal">
        <label class="block font-medium mb-1">Fecha del evento</label>
        <input type="date" name="fecha_hora"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div id="campo-tipo-fecha" class="hidden">
        <label class="block font-medium mb-1 mt-2">Tipo de fecha</label>
        <select name="tipo_fecha" id="tipo_fecha" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="fija">Fecha fija (ej: 10 de mayo)</option>
          <option value="variable">Fecha variable (ej: tercer domingo de junio)</option>
          <option value="indefinida">Fecha indefinida (ej: la primer semana de junio)</option>
        </select>
      </div>

      <div id="campo-fecha-referencia" class="hidden mt-4">
        <label class="block font-medium mb-1">Fecha de referencia</label>

        <input type="text" id="input-texto-fecha"
          placeholder="Ej: 3-domingo-junio"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hidden">

        <input type="date" id="input-calendario-fecha"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hidden">

        <input type="hidden" name="fecha_referencia" id="input-final-fecha-referencia">

        <p id="ayuda-fecha" class="text-gray-500 text-sm mt-1 hidden"></p>
      </div>

      <div>
        <label class="block font-medium mb-1">Horario del evento</label>
        <input type="time" name="hora_evento" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Descripción del evento</label>
        <textarea rows="4" name="descripcion" required placeholder="¿Que va a ofrecer el evento?"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
      </div>

      <div>
        <label class="block font-medium mb-1">Portada del evento</label>
        <input type="file" name="img_promocional" accept="image/*"
          class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-white file:bg-gray-900 hover:file:bg-gray-700">
      </div>

      <div>
        <label class="block font-medium mb-1">Dirección del evento</label>
        <input type="text" name="direccion" placeholder="¿Donde se llevará a cabo el evento?" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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

    <input type="hidden" id="input-evento-id" value="{{ $evento->pk_evento ?? ($evento_anual->pk_evento_anual ?? '') }}">
    <input type="hidden" id="input-es-anual" value="{{ isset($evento_anual) ? 'true' : 'false' }}">

    <div id="mensaje-notificacion" class="fixed top-5 right-5 z-50 hidden rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out"></div>


    <div class="mt-6" id="dropzone-container" style="display:none;">
      <label class="block font-medium mb-2">Imágenes adicionales</label>
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


  document.getElementById('form-evento').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    // Setear valor de es_anual desde input oculto
    const esAnualValue = document.getElementById("input-es-anual").value;
    formData.set("es_anual", esAnualValue);

    const esAnual = esAnualValue === 'true';

    if (esAnual) {
      const tipoFecha = document.getElementById('tipo_fecha').value;
      formData.set('tipo_fecha', tipoFecha);

      const inputFinal = document.getElementById('input-final-fecha-referencia');

      if (tipoFecha === 'fija') {
        const fechaCompleta = document.getElementById('input-calendario-fecha').value;
        if (fechaCompleta) {
          const [anio, mes, dia] = fechaCompleta.split('-');
          inputFinal.value = `${dia}-${mes}`; // Formato: 10-05
        }
      } else {
        inputFinal.value = document.getElementById('input-texto-fecha').value; // variable o indefinida
      }

      formData.set("fecha_referencia", inputFinal.value);
    }



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
        const eventoId = data.evento_id;

        // Ocultar botón de registrar
        document.getElementById('btn-registrar').style.display = 'none';

        // Mostrar el contenedor de Dropzone
        document.getElementById('dropzone-container').style.display = 'block';

        const dropzoneForm = document.getElementById('dropzoneImagenes');
        dropzoneForm.action = `/eventos/${eventoId}/imagenes`;

        // Evitar múltiples inicializaciones
        if (Dropzone.instances.length > 0) {
          Dropzone.instances.forEach(dz => dz.destroy());
        }

        // Inicializar Dropzone
        const myDropzone = new Dropzone("#dropzoneImagenes", {
          url: esAnual ?
            `/eventos-anuales/${eventoId}/imagenes` : `/eventos/${eventoId}/imagenes`,
          acceptedFiles: 'image/*',
          addRemoveLinks: true,
          dictRemoveFile: "Eliminar",
          dictDefaultMessage: "Arrastra o haz clic para subir imágenes",
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success: function(file, response) {
            const esAnual = document.getElementById('input-es-anual').value === 'true';

            if (esAnual && response.pk_img_evento_anual) {
              file.pk_img_evento = response.pk_img_evento_anual;
              console.log("Imagen anual guardada");
            } else if (!esAnual && response.pk_img_evento) {
              file.pk_img_evento = response.pk_img_evento;
              console.log("Imagen normal guardada");
            } else {
              console.warn("No se recibió ID de imagen válido.");
            }
          },
          removedfile: function(file) {
            const eventoId = document.getElementById('input-evento-id').value;
            const esAnual = document.getElementById('input-es-anual').value === 'true';
            const imagenId = file.pk_img_evento;
            console.log("Imagen borrada", );

            if (!imagenId) {
              console.warn("No se encontró el ID de la imagen en el objeto file.");
              return;
            }

            const url = esAnual ?
              `/eventos-anuales/imagenes/${imagenId}/eliminar` :
              `/eventos/imagenes/${imagenId}/eliminar`;

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
          error: function(file, response) {
            console.error("Error al subir imagen:", response);
          }
        });

        document.getElementById('error-mensajes').style.display = 'none';
        mostrarMensaje("Evento registrado. Ahora puedes subir imágenes adicionales.");

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
        mostrarMensaje("Error al registrar el evento.", "error");
      }
    } catch (error) {
      console.error("Error:", error);
      mostrarMensaje("Hubo un error inesperado.");
    }
  });
  document.getElementById('btn-finalizar').addEventListener('click', function() {
    mostrarMensaje("¡Registro completado!");
    setTimeout(() => {
      window.location.href = "{{ route('lista_eventos') }}";
    }, 1000);
  });


  document.addEventListener('DOMContentLoaded', function() {
    const switchAnual = document.getElementById('switch-eventoAnual');
    const formEvento = document.getElementById('form-evento');

    const rutaEventoNormal = "{{ route('registrando_evento') }}";
    const rutaEventoAnual = "{{ route('registrando_evento_anual') }}";

    const campoFechaNormal = document.getElementById('campo-fecha-normal');
    const campoTipoFecha = document.getElementById('campo-tipo-fecha');
    const campoFechaReferencia = document.getElementById('campo-fecha-referencia');
    const tipoFechaSelect = document.getElementById('tipo_fecha');
    const inputTextoFecha = document.getElementById('input-texto-fecha');
    const inputCalendarioFecha = document.getElementById('input-calendario-fecha');
    const ayudaFecha = document.getElementById('ayuda-fecha');
    const inputEsAnual = document.getElementById('input-es-anual');

    function toggleEventoAnual(activar) {

      if (switchAnual.checked) {
        const tipoFecha = tipoFechaSelect.value;

        if (tipoFecha === 'fija') {
          document.getElementById('input-final-fecha-referencia').value = inputCalendarioFecha.value;
        } else {
          document.getElementById('input-final-fecha-referencia').value = inputTextoFecha.value;
        }
      }
      if (activar) {
        formEvento.action = rutaEventoAnual;
        inputEsAnual.value = "true";

        campoFechaNormal.classList.add('hidden');
        campoTipoFecha.classList.remove('hidden');
        campoFechaReferencia.classList.remove('hidden');
        mostrarCampoFechaReferencia(tipoFechaSelect.value);
      } else {
        formEvento.action = rutaEventoNormal;
        inputEsAnual.value = "false";

        campoFechaNormal.classList.remove('hidden');
        campoTipoFecha.classList.add('hidden');
        campoFechaReferencia.classList.add('hidden');
        inputTextoFecha.classList.add('hidden');
        inputCalendarioFecha.classList.add('hidden');
        ayudaFecha.classList.add('hidden');
      }
    }

    function mostrarCampoFechaReferencia(tipo) {
      inputTextoFecha.classList.add('hidden');
      inputCalendarioFecha.classList.add('hidden');
      ayudaFecha.classList.remove('hidden');

      if (tipo === 'fija') {
        inputCalendarioFecha.classList.remove('hidden');
        ayudaFecha.textContent = "Selecciona una fecha fija del calendario (ej: 10 de mayo).";
      } else if (tipo === 'variable') {
        inputTextoFecha.classList.remove('hidden');
        inputTextoFecha.placeholder = 'Ej: 3-domingo-junio';
        ayudaFecha.textContent = "Formato: Ej: 3-domingo-junio";
      } else if (tipo === 'indefinida') {
        inputTextoFecha.classList.remove('hidden');
        inputTextoFecha.placeholder = 'Ej: 1-semana-junio';
        ayudaFecha.textContent = "Formato: Ej: 1-semana-junio";
      } else {
        ayudaFecha.classList.add('hidden');
      }
    }

    tipoFechaSelect.addEventListener('change', function() {
      mostrarCampoFechaReferencia(this.value);
    });

    switchAnual.addEventListener('change', function() {
      toggleEventoAnual(this.checked);
    });

    // Ejecutar una vez al cargar
    toggleEventoAnual(switchAnual.checked);
  });
</script>