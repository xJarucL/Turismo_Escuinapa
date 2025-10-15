<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar baner | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
    <div class="content">
        <x-nav />

        <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200">

            <a href="{{ route('administrador') }}" class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow mb-4" title="Volver">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <h1 class="text-2xl font-serif mb-6 text-center">Editar panel de bienvenida</h1>

            <form id="form-panel" action="{{ route('editando_panel') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block  font-medium mb-1">Titulo</label>
                    <input type="text" name="titulo" placeholder="Ej. Descubre Escuinapa" required
                        value="{{ $tituloPanel->titulo }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Subtitulo</label>
                    <input type="text" name="subtitulo" placeholder="Ej. La perla costera de Sinaloa" required
                        value="{{ $tituloPanel->subtitulo }}"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                @if ($errors->any())
                <div class="bg-red-100 text-red-700 border border-red-400 rounded p-4 mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="text-center pt-4">
                    <button type="submit"
                        class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">
                        Guardar cambios
                    </button>
                </div>
            </form>

        </div>
    </div>

    <x-footer />

</body>

</html>
