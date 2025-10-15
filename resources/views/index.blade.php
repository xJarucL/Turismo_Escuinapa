<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2C3E50',
            secondary: '#E74C3C',
            accent: '#F39C12',
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

  <x-nav />

  <section class="relative h-96 md:h-80 overflow-hidden rounded-lg shadow-lg">
    <div class="absolute inset-0 z-0">
      <div class="h-full w-full bg-[url('/img/esc_fondo.png')] bg-cover bg-center transition-opacity duration-1000 opacity-80"></div>
    </div>
    <div class="absolute inset-0 bg-black/30 z-0"></div>
    <div class="text-white animate__animated animate__fadeIn relative z-10 h-full flex flex-col justify-center items-center text-center p-6">
      <h1 class="text-4xl md:text-5xl font-serif mb-4">{{ $tituloPanel->titulo }}</h1>
      <p class="font-serif text-xl md:text-2xl mb-6">{{ $tituloPanel->subtitulo }}</p>
      <a href="#explorar"
        class="inline-block bg-accent hover:bg-yellow-600 text-white font-serif px-6 py-3 rounded-full transition transform hover:scale-105">
        Explorar Destinos
      </a>
    </div>
    </div>
  </section>


  <section id="explorar" class="py-16 relative overflow-hidden">
    <!-- Fondo con imagen (asegurando que esté detrás de todo) -->
    <div class="absolute inset-0 z-0">
      <div class="h-full w-full bg-[url('/img/playa-esc2.jpg')] bg-cover bg-center opacity-90"></div>
    </div>

    <!-- Línea de arriba -->
    <div class="absolute top-0 left-0 right-0 h-5 bg-[url('img/Lineas.png')] bg-repeat-x bg-[length:400px_auto] opacity-90"></div>

    <!-- Línea de abajo -->
    <div class="absolute bottom-0 left-0 right-0 h-5 bg-[url('img/Lineas2.png')] bg-repeat-x bg-[length:400px_auto] opacity-90"></div>

    <div class="max-w-7xl mx-auto px-4 relative z-10"> <!-- Añadido relative z-10 -->
        <x-alerta-evento />
      <h2 class="text-3xl font-serif text-black text-primary text-center mb-4">¿Qué deseas conocer?</h2>
      <p class="font-serif text-center text-black mb-12 max-w-2xl mx-auto">Explora los tesoros que Escuinapa tiene para ofrecerte</p>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <a href="{{ route('lista_lugares') }}" class="group relative rounded-xl h-64">
          <div class="relative h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500">
            <img src="img/teacapan_esc.webp" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Lugares">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
              <div>
                <h3 class="text-white text-2xl font-serif mb-1">Lugares de interés</h3>
                <p class="font-serif text-gray-200">Playas, naturaleza y sitios históricos</p>
                <div class="mt-2 w-12 h-1 bg-accent group-hover:w-20 transition-all duration-300"></div>
              </div>
            </div>
          </div>
          <div class="absolute -inset-1.5 border-2 border-accent rounded-xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
        </a>

        <a href="{{ route('lista_eventos') }}" class="group relative rounded-xl h-64">
          <div class="relative h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500">
            <img src="img/evento_esc.jpg" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Eventos">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
              <div>
                <h3 class="text-white text-2xl font-serif mb-1">Eventos culturales</h3>
                <p class="font-serif text-gray-200">Fiestas tradicionales y culturales</p>
                <div class="mt-2 w-12 h-1 bg-accent group-hover:w-20 transition-all duration-300"></div>
              </div>
            </div>
          </div>
          <div class="absolute -inset-1.5 border-2 border-accent rounded-xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
        </a>

        <a href="{{ route('lista_hoteles') }}" class="group relative rounded-xl h-64">
          <div class="relative h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500">
            <img src="img/hotel_esc.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Hoteles">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
              <div>
                <h3 class="text-white text-2xl font-serif mb-1">Hoteles</h3>
                <p class="font-serif text-gray-200">Hospedaje para tu estadía</p>
                <div class="mt-2 w-12 h-1 bg-accent group-hover:w-20 transition-all duration-300"></div>
              </div>
            </div>
          </div>
          <div class="absolute -inset-1.5 border-2 border-accent rounded-xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
        </a>

        <a href="{{ route('lista_restaurantes') }}" class="group relative rounded-xl h-64">
          <div class="relative h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500">
            <img src="img/restaurante_esc.jpg" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Restaurantes">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
              <div>
                <h3 class="text-white text-2xl font-serif mb-1">Restaurantes</h3>
                <p class="font-serif text-gray-200">Sabores locales que deleitarán tu paladar</p>
                <div class="mt-2 w-12 h-1 bg-accent group-hover:w-20 transition-all duration-300"></div>
              </div>
            </div>
          </div>
          <div class="absolute -inset-1.5 border-2 border-accent rounded-xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
        </a>

        <a href="{{ route('lista_comidas_tipicas') }}" class="group relative rounded-xl h-64">
          <div class="relative h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500">
            <img src="img/comidatipica_esc.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Comidas típicas">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
              <div>
                <h3 class="text-white text-2xl font-serif mb-1">Comidas típicas</h3>
                <p class="font-serif text-gray-200">Los platillos que definen nuestra cultura</p>
                <div class="mt-2 w-12 h-1 bg-accent group-hover:w-20 transition-all duration-300"></div>
              </div>
            </div>
          </div>
          <div class="absolute -inset-1.5 border-2 border-accent rounded-xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
        </a>

        <a href="{{ route('lista_presidentes') }}" class="group relative rounded-xl h-64">
          <div class="relative h-full overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500">
            <img
              src="img/presidente_esc.jpg"
              class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
              alt="Presidentes">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-6">
              <div>
                <h3 class="text-white text-2xl font-serif mb-1">Presidentes Municipales</h3>
                <p class="font-serif text-gray-200">La historia de quienes nos han gobernado</p>
                <div class="mt-2 w-12 h-1 bg-accent group-hover:w-20 transition-all duration-300"></div>
              </div>
            </div>
          </div>
          <div class="absolute -inset-1.5 border-2 border-accent rounded-xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
        </a>
      </div>
    </div>
  </section>


  <section class="py-16 relative overflow-hidden bg-white">
    <!-- Fondo de diosa -->
    <div class="absolute inset-0 z-0">
      <div class="absolute top-1/2 left-3/4 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md h-[120%] bg-[url('img/Diosa.png')] bg-contain bg-no-repeat bg-center opacity-50"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 relative z-10">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-serif text-primary mb-2 animate__animated animate__fadeIn">Nuestra Historia</h2>
        <div class="w-20 h-1 bg-accent mx-auto mb-6"></div>
        <p class="font-serif text-gray-600 max-w-2xl mx-auto">Conoce los orígenes y la evolución de este maravilloso municipio</p>
      </div>

      <div class="grid md:grid-cols-2 gap-8 items-center">
        <div class="relative group h-full">
          <img
            src="img/esc_fondo.png"
            alt="Escuinapa"
            class="w-full h-full max-h-[600px] object-cover rounded-xl shadow-xl transform group-hover:scale-105 transition duration-500">
          <div class="absolute -inset-[16px] border-2 border-accent rounded-xl opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>
        </div>

        <div class="h-full flex flex-col justify-center py-4">
          <div class="space-y-6 bg-white/0 p-6 rounded-lg">
            <div class="flex items-start">
              <div class="bg-accent rounded-full p-2 mr-4 mt-2"></div>
              <div>
                <h3 class="text-xl font-serif text-primary mb-1">Origen indígena</h3>
                <p class="font-serif text-gray-600">Fue habitado por pueblos como los tepehuanes y xiximes antes de la llegada de los españoles.</p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="bg-accent rounded-full p-2 mr-4 mt-2"></div>
              <div>
                <h3 class="text-xl font-serif text-primary mb-1">Época colonial</h3>
                <p class="font-serif text-gray-600">Se fundaron misiones religiosas con la llegada de los españoles en el siglo XVII.</p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="bg-accent rounded-full p-2 mr-4 mt-2"></div>
              <div>
                <h3 class="text-xl font-serif text-primary mb-1">Consolidación</h3>
                <p class="font-serif text-gray-600">Se convirtió oficialmente en municipio en 1915.</p>
              </div>
            </div>

            <div class="flex items-start">
              <div class="bg-accent rounded-full p-2 mr-4 mt-2"></div>
              <div>
                <h3 class="text-xl font-serif text-primary mb-1">Cultura viva</h3>
                <p class="font-serif text-gray-600">Conocido por fiestas tradicionales como El Mar de las Cabras y la Fiesta de los Pueblos.</p>
              </div>
            </div>
          </div>
          <div class="mt-8">
            <a href="{{ route('historia') }}" class="inline-block bg-primary hover:bg-gray-800 text-white font-serif px-6 py-3 rounded-full transition transform hover:scale-105">
              Conocer más sobre nuestra historia
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

    <x-footer />


</body>

</html>
