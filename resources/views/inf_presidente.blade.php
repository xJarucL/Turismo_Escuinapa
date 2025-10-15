<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detalles del presidente | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800 font-serif">

  <x-nav />

  <main class="flex-grow px-4 sm:px-6 py-8 max-w-7xl mx-auto w-full overflow-x-hidden">

    <!-- Botón de volver -->
    <a href="{{ route('lista_presidentes') }}" class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow mb-4 sm:mb-6" title="Volver">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </a>

    <div class="flex flex-row flex-nowrap justify-between items-center mb-8 gap-4 max-w-full overflow-hidden">
        <h1 class="text-3xl font-serif leading-tight max-w-full break-words overflow-hidden">
            {{ $presidente->nombre }}
        </h1>
        @auth
        <a href="{{ route('editar_presidente') }}"
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-200 transition text-gray-700 group flex-shrink-0"
            title="Editar información del presidente">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-gray-900" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            <span class="hidden sm:inline font-medium">Editar</span>
        </a>
        @endauth
    </div>

    <!-- Contenido principal con imagen y detalles -->
    <div x-data="{ modalOpen: false, modalImage: '' }" class="flex flex-col lg:flex-row w-full gap-6 lg:gap-8 items-start">

      <!-- Imagen (ocupará todo el ancho en móvil, ancho fijo en desktop) -->
      <div class="w-full lg:w-[450px] aspect-square bg-gray-100 border rounded-lg overflow-hidden cursor-pointer flex-shrink-0"
           @click="modalImage = '{{ asset('storage/' . $presidente->img_presidente) }}'; modalOpen = true">
        <img
          src="{{ asset('storage/' . $presidente->img_presidente) }}"
          alt="Imagen del presidente"
          class="object-cover w-full h-full" />
      </div>

      <!-- Detalles del presidente -->
      <div class="flex-grow text-left">
        <div class="mb-6">
          <p class="font-serif font-serif">Gobernatura:</p>
          <p class="font-serif text-base text-gray-700 leading-relaxed whitespace-pre-line">
            {{ \Carbon\Carbon::parse($presidente->fec_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($presidente->fec_fin)->format('d/m/Y') }}
          </p>
        </div>

        <div class="mb-6">
          <p class="font-serif font-serif">Contexto político:</p>
          <p class="font-serif text-base text-gray-700 leading-relaxed whitespace-pre-line">
            {{ $presidente->descripcion }}
          </p>
        </div>
      </div>

      <!-- Modal para imagen ampliada -->
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
