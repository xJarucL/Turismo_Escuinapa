<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Comida Típica | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
    <x-nav />

    <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200 mb-10">

        <a href="{{ route('informacion_comida') }}" class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow mb-4" title="Volver">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <h1 class="text-2xl font-serif mb-6 text-center">Editar Comida Típica</h1>
        @if ($errors->any())
        <div class="bg-red-100 text-red-700 border border-red-400 rounded p-4 mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="form-comida" action="{{ route('editando_comida_tipica') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-1">Nombre del platillo</label>
                <input type="text" name="nom_comida" placeholder="Ej. Machaca con huevo" required
                    value="{{ $comida->nom_comida }}"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block font-medium mb-1">Descripción</label>
                <textarea name="descripcion" rows="4" required placeholder="Breve descripción del platillo"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $comida->descripcion }}</textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Ingredientes</label>
                <textarea name="ingredientes" rows="3" required placeholder="Lista de ingredientes separados por coma"
                    class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $comida->ingredientes }}</textarea>
            </div>

            <div>
                <label class="block font-medium mb-2">Imagen del platillo</label>
                <div class="flex justify-center">
                    @php
                        $imgPath = $comida->img_comida ? asset('storage/' . $comida->img_comida) : asset('img/default.png');
                    @endphp
                    <label for="img_comida" class="cursor-pointer">
                        <img id="previewComidaImg" src="{{ $imgPath }}" data-original="{{ $imgPath }}" alt="Imagen del platillo"
                            class="w-48 h-48 object-cover rounded shadow border">
                    </label>
                    <input type="file" name="img_comida" id="img_comida" class="hidden" accept="image/*">
                </div>
                <p class="font-serif text-xs text-gray-500 text-center mt-2">Haz clic en la imagen para seleccionar una nueva imagen del platillo.</p>
            </div>

            <div>
                <label class="block font-medium mb-1">Categoría</label>
                <select name="fk_cat_comida" required class="w-full px-4 py-2 border rounded-md shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled>Selecciona una categoría</option>
                    @foreach ($categorias as $cat)
                    <option value="{{ $cat->pk_cat_comida }}" {{ $comida->fk_cat_comida == $cat->pk_cat_comida ? 'selected' : '' }}>
                        {{ $cat->nom_cat }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div id="error-mensajes" class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6" style="display: none;">
                <ul id="lista-errores" class="list-disc pl-5"></ul>
            </div>

            <div class="text-center pt-4">
                <button type="submit"
                    class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">
                    Guardar cambios
                </button>
            </div>
        </form>

    </div>

    <x-footer />


</body>

</html>
<script>
        // Mostrar imagen previa al seleccionar nuevo archivo
        document.getElementById('img_comida').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('previewComidaImg').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
