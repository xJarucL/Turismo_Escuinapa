<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Administración | Turismo Escuinapa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-white text-gray-900">


    <x-nav />

    <section class="relative h-64 md:h-80 overflow-hidden rounded-lg shadow-lg">
        <div class="absolute inset-0 z-0">
            <div class="h-full w-full bg-[url('/img/esc_fondo.png')] bg-cover bg-center transition-opacity duration-1000 opacity-80"></div>
        </div>
        <div class="absolute inset-0 bg-black/30 z-0"></div>
        <div class="relative z-10 h-full flex flex-col justify-center items-center text-center p-6">
            <h1 class="text-4xl md:text-5xl font-serif text-white mb-4 drop-shadow-lg">
                ¡Bienvenido <span class="text-yellow-300">{{ Auth::user()->nombre }}</span>!
            </h1>
            <p class="font-serif text-xl text-white/90 max-w-2xl drop-shadow-md">
                Estamos encantados de tenerte de vuelta en nuestro sistema.
            </p>
            <a href="{{route('editar_panel')}}"
                class=" mt-4 inline-flex items-center justify-center
                  bg-gradient-to-r from-yellow-500 to-yellow-500 hover:from-yellow-500 hover:to-yellow-500
                font-serif font-semibold px-8 py-3 rounded-full
                  shadow-lg hover:shadow-xl
                  transition-all duration-300 transform hover:scale-105 text-white">
                Editar banner
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-2xl font-serif text-center mb-10">Elige un apartado para agregar o ver información</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-center">

            <a href="{{ route('lista_usuarios') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
                <img src="/img/usuario_esc.png" class="w-48 h-48 object-cover rounded-t-lg mx-auto" alt="Usuarios">
                <div class="p-2">
                    <h3 class="font-serif text-lg">Usuarios</h3>
                </div>
            </a>

            <a href="{{ route ('lista_lugares') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
                <img src="img/teacapan_esc.webp" class="w-full h-48 object-cover rounded-t-lg" alt="Lugares de interés">
                <div class="p-2">
                    <h3 class="font-serif text-lg">Lugares de interés</h3>
                </div>
            </a>

            <a href="{{ route ('lista_eventos') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
                <img src="img/evento_esc.jpg" class="w-full h-48 object-cover rounded-t-lg" alt="Eventos">
                <div class="p-2">
                    <h3 class="font-serif text-lg">Eventos culturales</h3>
                </div>
            </a>

            <a href="{{ route ('lista_hoteles') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
                <img src="img/hotel_esc.jpeg" class="w-full h-48 object-cover rounded-t-lg" alt="Hoteles">
                <div class="p-2">
                    <h3 class="font-serif text-lg">Hoteles</h3>
                </div>
            </a>

            <a href="{{ route ('lista_restaurantes') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
                <img src="img/restaurante_esc.jpg" class="w-full h-48 object-cover rounded-t-lg" alt="Restaurantes">
                <div class="p-2">
                    <h3 class="font-serif text-lg">Restaurantes</h3>
                </div>
            </a>

            <a href="{{ route ('lista_comidas_tipicas') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col">
                <img src="img/comidatipica_esc.jpeg" class="w-full h-48 object-cover rounded-t-lg" alt="Comidas típicas">
                <div class="p-2 ">
                    <h3 class="font-serif text-lg">Comidas típicas</h3>
                </div>
            </a>

            <div class="hidden md:block"></div>

            <a href="{{ route ('lista_presidentes') }}" class="bg-white rounded-lg shadow hover:shadow-lg transition flex flex-col justify-center">
                <img src="img/presidente_esc.jpg" class="w-full h-48 object-cover rounded-t-lg" alt="Presidentes Municipales">
                <div class="p-2">
                    <h3 class="font-serif text-lg">Presidentes de Escuinapa</h3>
                </div>
            </a>

            <div class="hidden md:block"></div>
        </div>
    </main>

    <x-footer />


</body>

</html>
