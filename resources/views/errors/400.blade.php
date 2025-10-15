<title>400</title>

<x-nav />

<div class="relative min-h-screen flex items-center justify-center bg-gradient-to-r from-gray-100 via-yellow-100 to-gray-100 overflow-hidden px-6 py-10">

    <div class="absolute inset-0 bg-no-repeat bg-right bg-contain pointer-events-none z-0"
         style="background-image: url('/img/Diosa.png');">
    </div>

    <div class="z-10 text-center flex flex-col items-center space-y-6 max-w-2xl">

        <div class="text-[200px] font-extrabold text-yellow-300 animate-pulse select-none leading-none">
            400
        </div>

        <h1 class="text-3xl font-bold text-red-600">Solicitud incorrecta</h1>

        <p class="text-gray-700 text-lg">
            La solicitud enviada no fue válida o está incompleta.
        </p>
        <p class="text-gray-600 max-w-md">
            Esto puede suceder por un error en los datos enviados, un formulario mal llenado o un intento de acceso no permitido. Verifica la información e intenta de nuevo.
        </p>

        <a href="/"
           class="inline-block border-2 border-yellow-600 text-yellow-700 font-bold px-6 py-2 rounded-full hover:bg-yellow-600 hover:text-white transition-all duration-300">
            ← Volver al inicio
        </a>
    </div>
</div>

<x-footer />
