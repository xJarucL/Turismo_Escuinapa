<header class="fixed top-0 left-0 right-0 z-50 border-b border-gray-300 shadow-sm bg-red-600 transition-shadow duration-300">
  <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

    <button id="menuButton" class="text-3xl text-white md:hidden focus:outline-none transition-transform duration-200 hover:scale-110 active:scale-95">
      &#9776;
    </button>

    <img src="{{ asset('img/logo_nav.png') }}" class="w-10 h-10">

    <div class="hidden md:flex space-x-6 items-center text-white font-medium font-serif">

        <a href="{{ route('ayuntamiento') }}" class="hover:underline">Inicio</a>
        <a href="{{ route('lista_lugares') }}" class="hover:underline">Lugares de interés</a>
        <a href="{{ route('lista_eventos') }}" class="hover:underline">Eventos culturales</a>
        <a href="{{ route('lista_hoteles') }}" class="hover:underline">Hoteles</a>
        <a href="{{ route('lista_restaurantes') }}" class="hover:underline">Restaurantes</a>
        <a href="{{ route('lista_comidas_tipicas') }}" class="hover:underline">Comidas Típicas</a>
        <a href="{{ route('lista_presidentes') }}" class="hover:underline">Presidentes Municipales</a>

    </div>

    <div class="relative font-serif">
      <button id="profileButton" class="focus:outline-none transition-transform duration-200 hover:scale-110 active:scale-95">
        @if(Auth::user()->user_img)
        <img src="{{ asset('storage/' . Auth::user()->user_img) }}"
          alt="Foto de perfil"
          class="w-8 h-8 rounded-full inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        @else
        <img src="{{ asset('img/default_user.jpg') }}"
          alt="Sin imagen"
          class="w-8 h-8 rounded-full inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        @endif
      </button>

      <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl hidden z-50 border border-gray-200">
        <a href="{{ route('informacion_usuario') }}" class="px-4 py-3 text-sm text-gray-700 hover:bg-red-100 hover:text-red-800 transition-colors duration-200 flex items-center">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          Perfil
        </a>
        <a href="{{ route('logout') }}" class="px-4 py-3 text-sm text-red-600 hover:bg-red-100 transition-colors duration-200 flex items-center">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Cerrar sesión
        </a>
      </div>
    </div>
  </div>

  <div id="sideMenu" class="md:hidden font-serif fixed top-0 left-0 w-72 h-screen bg-gradient-to-b from-zinc-50 to-zinc-50 shadow-xl z-40 overflow-y-auto rounded-r-xl transform -translate-x-full transition-transform duration-300">
    <div class="p-4 border-b border-red-200 flex justify-between items-center bg-red-600 rounded-tr-xl">
      <span class="text-lg font-serif text-white">Opciones de Ayuntamiento</span>
      <button id="closeMenu" class="text-2xl text-white hover:text-red-200 transition-colors duration-200">&times;</button>
    </div>
    <nav class="flex flex-col space-y-3 p-4">
      <a href="{{ route('ayuntamiento') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Inicio
      </a>
      <a href="{{ route('lista_lugares') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Lugares de interés
      </a>
      <a href="{{ route('lista_eventos') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Eventos culturales
      </a>
      <a href="{{ route('lista_hoteles') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Hoteles
      </a>
      <a href="{{ route('lista_restaurantes') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Restaurantes
      </a>
      <a href="{{ route('lista_comidas_tipicas') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Comidas Típicas
      </a>
      <a href="{{ route('lista_presidentes') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Presidentes Municipales
      </a>
      <a href="{{ route('aviso-priv') }}" class="px-4 py-3 rounded-lg text-red-900 hover:bg-red-200 hover:text-red-800 font-medium transition-all duration-200 transform hover:translate-x-2 hover:shadow-sm">
        Aviso de privacidad
      </a>
    </nav>
  </div>

  <div id="overlay" class="fixed inset-0 bg-black opacity-50 hidden z-30"></div>
</header>

<div class="size-16"></div>

<script>
  const menuButton = document.getElementById('menuButton');
  const closeMenu = document.getElementById('closeMenu');
  const sideMenu = document.getElementById('sideMenu');
  const overlay = document.getElementById('overlay');
  const profileButton = document.getElementById('profileButton');
  const profileMenu = document.getElementById('profileMenu');
  const menuItems = document.querySelectorAll('#sideMenu nav a');

  function setActiveItem() {
    const currentPath = window.location.pathname;
    menuItems.forEach(item => {
      item.classList.remove('bg-red-300', 'text-red-900', 'font-serif', 'shadow-md');
      const href = item.getAttribute('href');
      if (href === currentPath || (currentPath.startsWith(href) && href !== '/')) {
        item.classList.add('bg-red-300', 'text-red-900', 'font-serif', 'shadow-md');
      }
    });
  }

  function toggleMenu() {
    sideMenu.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    document.body.style.overflow = sideMenu.classList.contains('-translate-x-full') ? '' : 'hidden';
  }

  function closeMenuHandler() {
    sideMenu.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
    document.body.style.overflow = '';
  }

  menuButton.addEventListener('click', toggleMenu);
  closeMenu.addEventListener('click', closeMenuHandler);
  overlay.addEventListener('click', closeMenuHandler);

  // Cerrar menú al hacer clic en cualquier opción
  menuItems.forEach(item => {
    item.addEventListener('click', closeMenuHandler);
  });

  // Mostrar/ocultar menú de perfil
  profileButton.addEventListener('click', (e) => {
    e.stopPropagation();
    profileMenu.classList.toggle('hidden');
  });

  // Cerrar menú de perfil si se hace clic fuera
  document.addEventListener('click', (e) => {
    if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
      profileMenu.classList.add('hidden');
    }
  });

  document.addEventListener('DOMContentLoaded', setActiveItem);
</script>
