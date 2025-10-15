<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar imágenes de comida típica | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col font-serif">

    <x-nav />

    <main class="flex-grow">
        <div class="max-w-xl mx-auto mt-10 mb-10 bg-white p-8 pb-20 rounded-lg shadow-md border border-gray-200">
            <div id="imagenes-actuales">
                <a href="{{ route('informacion_comida') }}"
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
                    title="Volver">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <label class="block font-medium mb-2 mt-4">Imágenes de la comida</label>

                @if ($comida->imagenes->count())
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($comida->imagenes as $imagen)
                            <div class="relative group border rounded overflow-hidden shadow bg-gray-100">
                                <img src="{{ asset('storage/' . $imagen->ruta) }}" alt="Imagen de comida típica"
                                    class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-105">

                                <form action="{{ route('comidas.imagenes.eliminar', ['imagenId' => $imagen->pk_img_comida_tipica]) }}"
                                    method="POST"
                                    class="absolute top-1 right-1 z-10"
                                    onsubmit="return false;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="eliminarImagen(this)"
                                        class="bg-red-600 hover:bg-red-700 text-white p-1 rounded-full shadow transition duration-200"
                                        title="Eliminar imagen">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="font-serif text-sm text-gray-500 italic">No hay imágenes cargadas para esta comida típica.</p>
                @endif
            </div>

            <!-- Dropzone para subir imágenes -->
            <div class="mt-6">
                <label class="block font-medium mb-2">Agregar imágenes a la comida</label>
                <form action="{{ route('comidas.imagenes.subir', ['comidaId' => $comida->pk_comida_tipica]) }}"
                    method="POST"
                    class="dropzone rounded-lg border-2 border-dashed border-gray-400 p-6 bg-white text-center"
                    id="dropzoneImagenes"
                    enctype="multipart/form-data">
                    @csrf
                </form>
                <p class="font-serif text-xs text-gray-500 mt-1">Puedes subir varias imágenes de la comida.</p>
            </div>

            <input type="hidden" id="input-comida-id" value="{{ $comida->pk_comida_tipica }}">

            <div class="text-center mt-6">
                <a href="{{ route('informacion_comida') }}">
                    <button type="button"
                        class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">
                        Guardar cambios
                    </button>
                </a>
            </div>
        </div>
    </main>

    <x-footer />


    <script>
        Dropzone.autoDiscover = false;

        document.addEventListener("DOMContentLoaded", function () {
            const comidaId = document.getElementById('input-comida-id').value;

            const myDropzone = new Dropzone("#dropzoneImagenes", {
                url: `/comidas/${comidaId}/imagenes`,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                dictRemoveFile: "Eliminar",
                dictDefaultMessage: "Arrastra o haz clic para subir imágenes",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (file, response) {
                    if (response.pk_img_comida_tipica) {
                        file.pk_img_comida_tipica = response.pk_img_comida_tipica;
                    } else {
                        console.warn("No se recibió ID válido");
                    }
                },
                removedfile: function (file) {
                    const imagenId = file.pk_img_comida_tipica;
                    if (!imagenId) return;

                    fetch(`/comidas/imagenes/${imagenId}/eliminar`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            file.previewElement.remove();
                        } else {
                            alert("Error al eliminar: " + data.mensaje);
                        }
                    })
                    .catch(err => console.error("Error eliminando imagen:", err));
                },
                error: function (file, response) {
                    console.error("Error al subir imagen:", response);
                }
            });
        });

        function eliminarImagen(button) {
            fetch(button.form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(button.form)
            }).then(() => location.reload());
        }
    </script>
</body>

</html>
