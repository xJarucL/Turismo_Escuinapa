<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalles del Restaurante | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800 font-serif">

    <x-nav />

    <main class="flex-grow px-6 py-10 max-w-7xl mx-auto">

        <a href="{{ route('lista_restaurantes') }}" onclick="" class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow mb-4" title="Volver">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <div class="flex flex-row flex-nowrap justify-between items-center mb-8 gap-4 max-w-full overflow-hidden">
        <h1 class="text-3xl font-serif leading-tight max-w-full break-words overflow-hidden">
            {{ $restaurante->nom_restaurante }}
        </h1>
        @auth
        <a href="{{ route('editar_restaurantes') }}"
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-200 transition text-gray-700 group flex-shrink-0"
            title="Editar información del restaurante">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-gray-900" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            <span class="hidden sm:inline font-medium">Editar</span>
        </a>
        @endauth
        </div>

        <div class="grid md:grid-cols-3 gap-6 text-sm mb-10">
            <p class="font-serif md:col-span-3"><strong>Descripción:</strong> {{$restaurante->descripcion}}</p>
            <p class="font-serif md:col-span-3">
            <p><strong>Dirección:</strong> {{$restaurante->direccion}}</p>
            <p><strong>Teléfono:</strong> {{$restaurante->tel_restaurante}}</p>
            <p><strong>Correo:</strong> {{$restaurante->email_restaurante}}</p>
            <p><strong>Horario:</strong> {{ formatearHora($restaurante->hora_apertura) }} - {{ formatearHora($restaurante->hora_cierre) }}</p>
            @if (!empty($restaurante->url_google_reseña))
            <p><strong>Enlace extra o reseñas:</strong>
                <a href="{{ $restaurante->url_google_reseña }}" class="text-blue-600 hover:underline" target="_blank">
                {{ parse_url($restaurante->url_google_reseña, PHP_URL_HOST) }}
                </a>
            </p>
            </p>
            @endif
        </div>

         @auth
        <div class="flex justify-end mb-4">
        <form action="{{ route('restaurante.seleccionar') }}" method="POST" onsubmit="return seleccionarRestauranteYRedirigir(this);">
            @csrf
            <input type="hidden" name="pk_restaurante" value="{{ $restaurante->pk_restaurante }}">
            <button
            type="submit"
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-200 transition text-gray-700 group"
            title="Editar imágenes del lugar"
            aria-label="Editar imágenes">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h2l2-3h8l2 3h2a2 2 0 012 2v9a2 2 0 01-2 2H3a2 2 0 01-2-2V9a2 2 0 012-2z" />
                <circle cx="12" cy="14" r="3" stroke-width="2" />
            </svg>
            <span class="hidden sm:inline font-medium">Editar imágenes</span>
            </button>
        </form>
        </div>
        @endauth

        <div
      x-data="{ modalOpen: false, modalImage: '' }"
      class="grid grid-cols-3 gap-4">
      @forelse ($restaurante->imagenes as $imagen)
      <div
        class="aspect-square bg-gray-100 border rounded-lg flex items-center justify-center overflow-hidden cursor-pointer"
        @click="modalImage = '{{ asset('storage/' . $imagen->ruta) }}'; modalOpen = true">
        <img src="{{ asset('storage/' . $imagen->ruta) }}" alt="Imagen del resta$restaurante" class="object-cover w-full h-full">
      </div>
      @empty
      <p class="font-serif col-span-3 text-gray-500">No hay imágenes disponibles para este restaurante.</p>
      @endforelse

      <div
        x-show="modalOpen"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
        @click.self="modalOpen = false">
        <div class="relative max-w-3xl max-h-[90vh] p-4">
          <img
            :src="modalImage"
            alt="Vista ampliada"
            class="w-full h-full object-contain rounded-lg">
          <button
            class="absolute -top-3 -right-3 bg-white/90 hover:bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 focus:outline-none"
            @click="modalOpen = false"
            aria-label="Cerrar modal">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    </main>

    <x-footer />

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>

</html>
<script>
  // Redirigir al restaurante seleccionado
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.enlace-restaurante').forEach(function (enlace) {
      enlace.addEventListener('click', function (e) {
        e.preventDefault();

        const restauranteId = this.dataset.id;

        fetch("{{ route('restaurante.seleccionar') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              pk_restaurante: restauranteId
            })
          })
          .then(response => {
            if (response.ok) {
              window.location.href = "{{ route('informacion_restaurante') }}";
            } else {
              alert("Error al seleccionar restaurante.");
            }
          })
          .catch(() => alert("Error al comunicarse con el servidor."));
      });
    });
  });

  function seleccionarRestauranteYRedirigir(form) {
    const formData = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    }).then(response => {
      if (response.ok) {
        window.location.href = "{{ route('restaurantes.imagenes.cargar') }}";
      } else {
        alert("Error al seleccionar el restaurante.");
      }
    });

    return false;
  }
</script>
