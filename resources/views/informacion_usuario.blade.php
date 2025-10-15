<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Usuario | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body>

    <body class="bg-gray-100 text-gray-900 font-serif">

        <x-nav />


        <div class="max-w-2xl mx-auto mt-12 mb-12 bg-white shadow-md rounded-lg p-8 border border-gray-300">

            @if(auth()->user()->fk_tipo_usuario == '1')
            <a href="{{route('administrador')}}" onclick=""
                class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
                title="Volver">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            @elseif(auth()->user()->fk_tipo_usuario == '2')
            <a href="{{route('ayuntamiento')}}" onclick=""
                class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
                title="Volver">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            @endif

            <h1 class="text-2xl font-serif mb-6 text-center">Información del Usuario</h1>

            <div class="flex justify-center mb-6">
                @if($usuario->user_img)
                <img src="{{ asset('storage/' . $usuario->user_img) }}" alt="Foto de perfil" class="w-36 h-36 rounded-full object-cover shadow">
                @else
                <p class="font-serif text-sm text-gray-500">No hay imagen de perfil</p>
                @endif
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-base font-serif text-gray-800">Nombre:</label>
                    <input type="text" value="{{ $usuario->nombre }}" disabled
                        class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-lg bg-gray-100 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-base font-serif text-gray-800">Apellido paterno:</label>
                    <input type="text" value="{{ $usuario->apaterno }}" disabled
                        class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-lg bg-gray-100 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-base font-serif text-gray-800">Apellido materno:</label>
                    <input type="text" value="{{ $usuario->amaterno }}" disabled
                        class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-lg bg-gray-100 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-base font-serif text-gray-800">Email:</label>
                    <input type="text" value="{{ $usuario->email }}" disabled
                        class="w-full mt-2 border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-lg bg-gray-100 cursor-not-allowed">
                </div>
            </div>
            <div class="text-end mb-6 pt-6">
                <a href="{{ route('editar_usuario') }}" class="inline-block bg-gray-900 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition">Editar</a>
            </div>
        </div>

    <x-footer />

    </body>

</html>
