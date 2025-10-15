<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar evento | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">

    <x-nav />

    <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200 mb-10">
        <a href="{{route('informacion_evento')}}" onclick=""
            class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
            title="Volver">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <h1 class="text-2xl font-serif mb-6 text-center">Editar Evento</h1>

        @if ($tipo === 'anual')
        <form id="form-evento" method="POST" action="{{ route('editando_evento_anual') }}" enctype="multipart/form-data" class="space-y-5">
            @else
            <form id="form-evento" method="POST" action="{{ route('editando_evento') }}" enctype="multipart/form-data" class="space-y-5">
                @endif

                @csrf
                @method('PUT')

                <div>
                    <label class="block font-medium mb-1">Nombre del evento</label>
                    <input type="text" name="nom_evento" value="{{ $evento->nom_evento }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                @if ($tipo === 'anual')
                <!-- {{-- Tipo de fecha --}} -->
                <input type="hidden" id="input-es-anual" value="{{ $tipo === 'anual' ? 'true' : 'false' }}">
                <div>
                    <label class="block font-medium mb-1 mt-2">Tipo de fecha</label>
                    <select name="tipo_fecha" id="tipo_fecha"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="fija" {{ $evento->tipo_fecha === 'fija' ? 'selected' : '' }}>Fecha fija (ej: 10 de mayo)</option>
                        <option value="variable" {{ $evento->tipo_fecha === 'variable' ? 'selected' : '' }}>Fecha variable (ej: tercer domingo de junio)</option>
                        <option value="indefinida" {{ $evento->tipo_fecha === 'indefinida' ? 'selected' : '' }}>Fecha indefinida (ej: primera semana de junio)</option>
                    </select>
                </div>

                <div id="campo-fecha-referencia" class="mt-4">
                    <label class="block font-medium mb-1">Fecha de referencia</label>

                    <input type="text" id="input-texto-fecha"
                        value="{{ in_array($evento->tipo_fecha, ['variable', 'indefinida']) ? $evento->fecha_referencia : '' }}"
                        placeholder="Ej: 3-domingo-junio"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hidden">

                    <input type="date" name="fecha_referencia_calendario" id="input-calendario-fecha"
                        value="{{ $evento->tipo_fecha !== 'variable' ? fechaReferenciaConAnioActual($evento->fecha_referencia) : '' }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $evento->tipo_fecha !== 'variable' ? '' : 'hidden' }}">

                    <input type="hidden" name="fecha_referencia" id="input-final-fecha-referencia" value="">

                    <p id="ayuda-fecha" class="text-gray-500 text-sm mt-1 {{ $evento->tipo_fecha ? '' : 'hidden' }}"></p>
                </div>


                <div>
                    <label class="block font-medium mb-1">Hora del evento</label>
                    <input type="time" name="hora_evento" value="{{ \Carbon\Carbon::parse($evento->hora_evento)->format('H:i') }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Dirección del evento</label>
                    <input type="text" name="direccion" value="{{ $evento->direccion }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div>
                    <label class="block font-medium mb-1">Fecha del evento</label>
                    <input type="date" name="fecha_hora"
                        value="{{ old('fecha_hora', \Carbon\Carbon::parse($evento->fecha_hora)->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Hora del evento</label>
                    <input type="time" name="hora_evento" value="{{ $evento->hora_evento }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Dirección del evento</label>
                    <input type="text" name="direccion" value="{{ $evento->direccion }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @endif

                <div>
                    <label class="block font-medium mb-1">Descripción del evento</label>
                    <textarea name="descripcion" rows="4"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>{{ $evento->descripcion }}</textarea>
                </div>

                <div class="flex justify-center">
                    @php
                    $imgPath = $evento->img_promocional ? asset('storage/' . $evento->img_promocional) : asset('img/default.png');
                    @endphp
                    <label for="img_promocional" class="cursor-pointer">
                        <img id="previewEventoImg" src="{{ $imgPath }}" data-original="{{ $imgPath }}" alt="Imagen de portada"
                            class="w-48 h-48 object-cover rounded shadow border">
                    </label>
                    <input type="file" name="img_promocional" id="img_promocional" class="hidden" accept="image/*">
                </div>
                <p class="font-serif text-xs text-gray-500 text-center mt-2">Haz clic en la imagen para seleccionar una nueva imagen de portada.</p>

                <div id="error-mensajes" class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6" style="display: none;">
                <ul id="lista-errores" class="list-disc pl-5"></ul>
                </div>

                <div class="text-center pt-4">
                    <button id="btn-guardar-evento" type="submit"
                        class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">
                        Guardar cambios
                    </button>

                </div>

            </form>
            <div id="mensaje-notificacion" class="fixed top-5 right-5 z-50 hidden rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out"></div>

    </div>

    <x-footer />

</body>

</html>
@push('scripts')
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

    document.addEventListener('DOMContentLoaded', function() {
        const imgInput = document.getElementById('img_promocional');
        const previewImg = document.getElementById('previewEventoImg');
        const originalSrc = previewImg.getAttribute('data-original');

        imgInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        window.restoreEventoImage = function() {
            previewImg.src = originalSrc;
            imgInput.value = '';
        };
    });

    // Ejecutar una vez al cargar para mostrar el campo correcto según tipo seleccionado
    document.addEventListener('DOMContentLoaded', function () {
    const tipoFecha = document.getElementById('tipo_fecha');
    if (tipoFecha) {
        mostrarCampoFechaReferencia(tipoFecha.value);
    }
    });

    document.getElementById('form-evento').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        const esAnualInput = document.getElementById("input-es-anual");
        const esAnualValue = esAnualInput ? esAnualInput.value : 'false';
        formData.set("es_anual", esAnualValue);

        const esAnual = esAnualValue === 'true';

        if (esAnual) {
            const tipoFecha = document.getElementById('tipo_fecha').value;
            formData.set('tipo_fecha', tipoFecha);

            const inputFinal = document.getElementById('input-final-fecha-referencia');

            let fechaReferencia = '';

            if (tipoFecha === 'fija') {
                const fechaCompleta = document.getElementById('input-calendario-fecha').value;
                if (fechaCompleta) {
                    const [anio, mes, dia] = fechaCompleta.split('-');
                    fechaReferencia = `${dia}-${mes}`;  // Formato: 10-05
                }
            } else {
                const valorTexto = document.getElementById('input-texto-fecha').value;
                if (valorTexto.trim()) {
                    fechaReferencia = valorTexto.trim();
                }
            }

            inputFinal.value = fechaReferencia;
            formData.set("fecha_referencia", fechaReferencia);
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
                mostrarMensaje("Evento actualizado correctamente.");
                document.getElementById('error-mensajes').style.display = 'none';
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
                mostrarMensaje("Error al actualizar el evento.", "error");
            }

        } catch (error) {
            console.error(error);
            document.getElementById('error-mensajes').style.display = 'none';
            mostrarMensaje("Evento actualizado.", "success");

            setTimeout(() => {
                window.location.href = "{{ route('informacion_evento') }}";
            }, 1000);
        }
    });


  // Campo fecha anual
  const tipoFechaSelect = document.getElementById("tipo_fecha");
  const inputTextoFecha = document.getElementById("input-texto-fecha");
  const inputCalendarioFecha = document.getElementById("input-calendario-fecha");
  const ayudaFecha = document.getElementById("ayuda-fecha");

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

  if (tipoFechaSelect) {
    tipoFechaSelect.addEventListener('change', function() {
      mostrarCampoFechaReferencia(this.value);
    });
  }

</script>
