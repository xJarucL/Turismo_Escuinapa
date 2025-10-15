<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar imágenes del evento | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col font-serif">
    <x-nav />

    <main class="flex-grow">
        <div class="max-w-xl mx-auto mt-10 mb-10 bg-white p-8 rounded-lg shadow-md border border-gray-200">
            <a href="{{ route('informacion_evento') }}"
                class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow mb-4"
                title="Volver">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <label class="block font-medium mb-2">Imágenes del evento</label>

            @if ($evento->imagenes->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                    @foreach ($evento->imagenes as $imagen)
                        <div class="relative group border rounded overflow-hidden shadow bg-gray-100">
                            <img src="{{ asset('storage/' . $imagen->ruta) }}" alt="Imagen del evento"
                                class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-105">

                            <form action="{{ $tipo === 'anual'
                                    ? route('eventos.anuales.imagenes.eliminar', ['imagenId' => $imagen->pk_img_evento_anual])
                                    : route('eventos.imagenes.eliminar', ['imagenId' => $imagen->pk_img_evento]) }}"
                                method="POST"
                                class="absolute top-1 right-1 z-10"
                                onsubmit="return false;">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="eliminarImagen(this)"
                                    class="bg-red-600 hover:bg-red-700 text-white p-1 rounded-full shadow transition duration-200"
                                    title="Eliminar imagen">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="font-serif text-sm text-gray-500 italic mb-6">No hay imágenes cargadas para este evento.</p>
            @endif

            <!-- Dropzone -->
            <div class="mb-6">
                <label class="block font-medium mb-2">Agregar imágenes al evento</label>
                <form action="{{ $tipo === 'anual'
                    ? route('eventos.anuales.imagenes.subir', ['eventoId' => $evento->pk_evento_anual])
                    : route('eventos.imagenes.subir', ['eventoId' => $evento->pk_evento]) }}"
                    method="POST"
                    class="dropzone rounded-lg border-2 border-dashed border-gray-400 p-6 bg-white text-center"
                    id="dropzoneImagenes"
                    enctype="multipart/form-data">
                    @csrf
                </form>
                <p class="font-serif text-xs text-gray-500 mt-1">Puedes subir varias imágenes del evento.</p>
            </div>

            <input type="hidden" id="input-evento-id" value="{{ $tipo === 'anual' ? $evento->pk_evento_anual : $evento->pk_evento }}">
            <input type="hidden" id="input-es-anual" value="{{ $tipo === 'anual' ? 'true' : 'false' }}">

            <div class="text-center">
                <a href="{{ route('informacion_evento') }}">
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
            const eventoId = document.getElementById('input-evento-id').value;
            const esAnual = document.getElementById('input-es-anual').value === 'true';

            const myDropzone = new Dropzone("#dropzoneImagenes", {
                url: esAnual ? `/eventos-anuales/${eventoId}/imagenes` : `/eventos/${eventoId}/imagenes`,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                dictRemoveFile: "Eliminar",
                dictDefaultMessage: "Arrastra o haz clic para subir imágenes",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (file, response) {
                    const key = esAnual ? 'pk_img_evento_anual' : 'pk_img_evento';
                    if (response[key]) {
                        file.pk_img_evento = response[key];
                    } else {
                        console.warn("No se recibió ID válido");
                    }
                },
                removedfile: function (file) {
                    const imagenId = file.pk_img_evento;
                    if (!imagenId) return;

                    const url = esAnual ? `/eventos-anuales/imagenes/${imagenId}/eliminar` : `/eventos/imagenes/${imagenId}/eliminar`;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            file.previewElement.remove();
                        } else {
                            alert("Error al eliminar: " + data.mensaje);
                        }
                    }).catch(err => {
                        console.error("Error eliminando imagen:", err);
                    });
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
