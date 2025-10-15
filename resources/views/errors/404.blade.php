<title>404</title>

<x-nav />

<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-r from-gray-100 via-yellow-100 to-gray-100 overflow-hidden px-6 py-10">

    <div class="absolute inset-0 bg-no-repeat bg-right bg-contain pointer-events-none z-0"
         style="background-image: url('/img/Diosa.png');">
    </div>

    <div class="z-10 text-center flex flex-col items-center space-y-6 max-w-2xl">

        <div class="text-[200px] font-extrabold text-yellow-300 animate-pulse select-none leading-none">
            404
        </div>

        <h1 class="text-3xl font-bold text-red-600">Página no encontrada</h1>

        <p class="text-gray-700 text-lg">
            Oops, parece que este rincón del sitio está vacío o el enlace ha sido eliminado.
        </p>
        <p class="text-gray-600 max-w-md">
            Pero no te preocupes, puedes regresar al inicio o explorar otras secciones de nuestra página.
        </p>

        <a href="/"
           class="inline-block border-2 border-yellow-600 text-yellow-700 font-bold px-6 py-2 rounded-full hover:bg-yellow-600 hover:text-white transition-all duration-300">
            ← Volver al inicio
        </a>
    </div>
</div>

<x-footer />
