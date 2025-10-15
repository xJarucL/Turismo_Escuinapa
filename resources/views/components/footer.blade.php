<style>
  html,
  body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
  }

  #contenido-principal {
    flex: 1;
  }
</style>

<script>
  // Este script mueve todo lo que hay en <body> excepto el footer a un div con id "contenido-principal"
  document.addEventListener('DOMContentLoaded', () => {
    const bodyChildren = Array.from(document.body.children);
    const footer = document.querySelector('footer');

    const wrapper = document.createElement('div');
    wrapper.id = 'contenido-principal';
    wrapper.style.flex = '1';
    document.body.insertBefore(wrapper, footer);

    bodyChildren.forEach(el => {
      if (el !== footer && el.tagName !== 'SCRIPT') {
        wrapper.appendChild(el);
      }
    });
  });
</script>

<footer class="bg-gray-900 text-white py-8 shadow-inner border-t border-gray-300 font-serif">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-2">
      <div class="flex items-center gap-4">
        <img src="{{ asset('img/Grecas 1.png') }}" alt="Turismo Escuinapa" class="h-10">
        <img src="{{ asset('img/Grecas 2.png') }}" alt="Turismo Escuinapa" class="h-10">
        <img src="{{ asset('img/logorojo.png') }}" alt="Escudo Escuinapa" class="h-12">
        <img src="{{ asset('img/Grecas 3.png') }}" alt="Turismo Escuinapa" class="h-10">
        <img src="{{ asset('img/Grecas 4.png') }}" alt="Turismo Escuinapa" class="h-10">
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-center sm:text-left">
        <div>
          <h3 class="font-bold text-lg text-yellow-400 mb-2">Ayuntamiento</h3>
          <p class="text-sm">Palacio Municipal S/N</p>
          <p class="text-sm">Tel: 695 953 0019</p>
          <p class="text-sm">contacto@escuinapa.gob.mx</p>
        </div>

        <div>
          <h3 class="font-bold text-lg text-red-400 mb-2">Emergencias</h3>
          <p class="text-sm">Policía y Protección Civil: 695 953 0018</p>
          <p class="text-sm">Cruz Roja: 695 953 3155</p>
        </div>
      </div>
    </div>

    <hr class="border-gray-700 my-4">

    <div class="flex flex-col items-center gap-2 mt-2">
      <p class="font-serif text-sm">© 2025 Turismo Escuinapa. Todos los derechos reservados.</p>
      @auth
      <div>
        <a href="{{ route('aviso-priv') }}" class="font-serif text-sm hover:text-yellow-300 transition-colors">Aviso de privacidad.</a>
      </div>
      @endauth
    </div>

    <div class="flex justify-center items-center gap-4 mt-2">
      <a href="https://www.facebook.com/share/1BD8inbw4r/" class="text-white hover:text-blue-400 transition-colors">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
        </svg>
      </a>

      <div class="group relative">
        <img src="{{ asset('img/utesc.webp') }}" alt="Escudo Escuinapa" class="h-6 hover:scale-105 transition-transform cursor-pointer">
        <div class="fixed inset-0 flex items-center justify-center pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-50">
          <div class="bg-gradient-to-br from-blue-50 to-white text-gray-800 px-6 py-4 rounded-lg shadow-xl border border-blue-100 w-full max-w-md mx-4 transform translate-y-[-50%]">
            <span class="font-bold text-center block mb-3 text-lg">Elaborado por alumnos de Ingeniería en Desarrollo y Gestión de Software:</span>
            <span class="font-bold text-center block mb-3 text-green-600 text-xl">Universidad Tecnológica de Escuinapa</span>
            <ul class="space-y-2">
              <li class="flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                <span>Arce Leyva Luis Arturo.</span>
              </li>
              <li class="flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                <span>Cárdenas Tirado Jaruny Guadalupe.</span>
              </li>
              <li class="flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                <span>Heredia Silva Brandon Said.</span>
              </li>
              <li class="flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                <span>Salazar Medina Ángel Ariel.</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>
  </div>
  <div class="flex justify-between items-center w-full">
    @if (Auth::check())
    <div class=""></div>
    @else
    <div class="ml-10 opacity-25">
      <a href="{{route('login')}}" class="text-white hover:text-blue-400 transition-colors">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 2a5 5 0 00-5 5v2H6a2 2 0 00-2 2v9a2 2 0 002 2h12a2 2 0 002-2v-9a2 2 0 00-2-2h-1V7a5 5 0 00-5-5zm-3 7V7a3 3 0 016 0v2H9z" />
        </svg>
      </a>
    </div>
    @endif
  </div>
</footer>