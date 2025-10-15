<title>401</title>

<x-nav />

<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-r from-gray-100 via-yellow-100 to-gray-100 overflow-hidden px-6 py-10">

    <div class="absolute inset-0 bg-no-repeat bg-right bg-contain pointer-events-none z-0"
         style="background-image: url('/img/Diosa.png');">
    </div>

    <div class="z-10 text-center flex flex-col items-center space-y-6 max-w-2xl">

        <div class="text-[200px] font-extrabold text-yellow-300 animate-pulse select-none leading-none">
            401
        </div>

        <h1 class="text-3xl font-bold text-red-600">No autorizado</h1>

        <p class="text-gray-700 text-lg">
            No tienes permisos para acceder a esta página.
        </p>
        <p class="text-gray-600 max-w-md">
            Es posible que necesites iniciar sesión o que tu cuenta no tenga los permisos necesarios. Por favor, verifica tus credenciales e intenta de nuevo.
        </p>

        <a href="/"
           class="inline-block border-2 border-yellow-600 text-yellow-700 font-bold px-6 py-2 rounded-full hover:bg-yellow-600 hover:text-white transition-all duration-300">
            ← Volver al inicio
        </a>
    </div>
</div>

<x-footer />
