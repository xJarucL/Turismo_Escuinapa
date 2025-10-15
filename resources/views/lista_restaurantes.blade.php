<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Restaurantes | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="flex flex-col min-h-screen bg-white text-gray-900 font-serif">

    <x-nav />

    @auth

    <main class="flex-grow px-4 py-10 w-full">
        <div class="max-w-7xl mx-auto px-4 flex justify-between">
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
            <a href="{{ route('registro_restaurante') }}"
                class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                Registrar restaurante
            </a>
        </div>

        <div class="flex justify-center items-center mb-6">
            <h1 class="text-3xl font-serif">Restaurantes</h1>
        </div>

        <div class="flex justify-center mb-10">
            <div class="relative w-full max-w-md">
                <input id="buscador-restaurantes" type="text" placeholder="Buscar restaurante..." class="w-full pl-4 pr-10 py-2 border border-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-red-400">
                <span class="absolute right-3 top-2.5 text-gray-900">🔍</span>
            </div>
        </div>

        <!-- Toggle para ver los eventos que estan inactivos -->
        <div class="max-w-6xl mx-auto px-6 mb-8">
            <label for="switch-estatus" class="inline-flex items-center me-5 cursor-pointer">
                <input type="checkbox" id="switch-estatus" class="sr-only peer">
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus700ring-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-700-600 peer-checked:bg-green-600 dark:peer-700d:bg-green-600 dot"></div>
                <span id="toggle-label" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-500">Mostrar inactivos</span>
            </label>
        </div>

        <div id="contenedor-restaurantes" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-7xl mx-auto px-4"> @foreach ($restaurantes as $restaurante)
            <!-- Tarjeta restaurante 1 -->
            <div class="restaurante-card border rounded-lg p-3 text-center shadow-xs transition-all duration-300 hover:shadow-md hover:-translate-y-1 mx-auto w-full max-w-[280px]">
                <div class="bg-gray-100 rounded-md aspect-square mb-2 overflow-hidden">
                    <a href="{{route('informacion_restaurante')}}" class="enlace-restaurante block w-full h-full" data-id="{{ $restaurante->pk_restaurante }}">
                        <img src="{{ asset('storage/' . $restaurante->img_promocional) }}" alt="Restaurante" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105" />
                    </a>
                </div>
                <a href="{{route('informacion_restaurante')}}" class="enlace-restaurante" data-id="{{ $restaurante->pk_restaurante }}">
                    <h3 class="font-medium text-lg nombre-restaurante hover:text-red-600 transition-colors duration-300 mb-0.5 line-clamp-1">{{ $restaurante->nom_restaurante }}</h3>
                </a>
                <p class="font-serif text-xs text-gray-500 mb-1.5 line-clamp-2">{{ \Illuminate\Support\Str::limit($restaurante->descripcion, 50, '...') }}</p>

                @if ($restaurante->estatus == 1)
                <button
                    class="btn-cambiar-estatus bg-red-200 text-red-700 px-4 py-1 rounded text-sm mt-4 transition-all hover:-translate-y-1 hover:shadow-md active:translate-y-0"
                    data-id="{{ $restaurante->pk_restaurante }}"
                    data-estatus="0">Deshabilitar</button>
                @else
                <button
                    class="btn-cambiar-estatus bg-green-200 text-green-700 px-4 py-1 rounded text-sm mt-4 transition-all hover:-translate-y-1 hover:shadow-md active:translate-y-0"
                    data-id="{{ $restaurante->pk_restaurante }}"
                    data-estatus="1">Activar</button>
                @endif
            </div>
            @endforeach
            @else
            <main class="flex-grow px-6 py-10 w-full max-w-7xl mx-auto">
                <a href="{{route('index')}}" onclick=""
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
                    title="Volver">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div class="flex justify-center items-center mb-6">
                    <h1 class="text-3xl font-serif">Restaurantes</h1>
                </div>

                <div class="flex justify-center mb-10">
                    <div class="relative w-full max-w-md">
                        <input id="buscador-restaurantes-public" type="text" placeholder="Buscar restaurante..."
                            class="w-full pl-4 pr-10 py-2 border border-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-red-400">
                        <span class="absolute right-3 top-2.5 text-gray-900">🔍</span>
                    </div>
                </div>

                <div class="w-full h-6 bg-[url('img/Lineas.png')] bg-repeat-x bg-[length:290px_auto] opacity-90 mb-6"></div>

                <div id="contenedor-restaurantes" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-7xl mx-auto px-6">
                    @foreach ($restaurantes_public as $restaurante)
                    <div class="restaurante-card border rounded-lg p-3 text-center shadow-xs transition-all duration-300 hover:shadow-md hover:-translate-y-1 mx-auto w-full max-w-[280px]">
                        <div class="bg-gray-100 rounded-md aspect-square mb-2 overflow-hidden">
                            <a href="{{route('informacion_restaurante')}}" class="enlace-restaurante block w-full h-full" data-id="{{ $restaurante->pk_restaurante }}">
                                <img src="{{ asset('storage/' . $restaurante->img_promocional) }}" alt="Restaurante" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105" />
                            </a>
                        </div>
                        <a href="{{route('informacion_restaurante')}}" class="enlace-restaurante block" data-id="{{ $restaurante->pk_restaurante }}">
                            <h3 class="font-medium text-lg nombre-restaurante hover:text-red-600 transition-colors duration-300 mb-0.5 line-clamp-1">{{ $restaurante->nom_restaurante }}</h3>
                        </a>
                        <p class="font-serif text-xs text-gray-500 mb-1.5 line-clamp-2">{{ \Illuminate\Support\Str::limit($restaurante->descripcion, 50, '...') }}</p>
                    </div>
                    @endforeach
                    @endauth


            </main>

            <x-footer />

</body>

</html>

<script>
    // Rediregir al evento seleccionado

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.enlace-restaurante').forEach(function(enlace) {
            enlace.addEventListener('click', function(e) {
                e.preventDefault();
                const restauranteId = this.dataset.id;

                fetch("{{ route('restaurante.seleccionar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            pk_restaurante: restauranteId
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.href = "{{ route('informacion_restaurante') }}";
                        } else {
                            console.error('Error al seleccionar el restaurante');
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                    });
            });
        });
    });


    // Cambiar estatus, mostrar restaurantes activos e inactivos y buscar restaurantes
    document.addEventListener("DOMContentLoaded", function() {
        const csrfToken = '{{ csrf_token() }}';
        const switchEstatus = document.getElementById('switch-estatus');
        const mensajeSinRestaurantes = document.getElementById('mensaje-sin-restaurantes');
        const buscador = document.getElementById("buscador-restaurantes");
        const contenedorRestaurantes = document.getElementById("contenedor-restaurantes");

        function filtrarRestaurantes() {
            const mostrarInactivos = switchEstatus.checked;
            const query = buscador.value.toLowerCase().trim();
            let restaurantesVisibles = 0;

            document.querySelectorAll('.restaurante-card').forEach(card => {
                const boton = card.querySelector('.btn-cambiar-estatus');
                const estatus = boton.dataset.estatus === "0" ? "1" : "0";
                const nombre = card.querySelector(".nombre-restaurante").textContent.toLowerCase();
                const coincideBusqueda = nombre.includes(query);

                let visible = false;

                if (mostrarInactivos && estatus === "0" && coincideBusqueda) {
                    visible = true;
                } else if (!mostrarInactivos && estatus === "1" && coincideBusqueda) {
                    visible = true;
                }

                card.style.display = visible ? 'block' : 'none';
                if (visible) restaurantesVisibles++;

            });


            const mensajeExistente = document.getElementById("sin-resultados");

            if (restaurantesVisibles === 0) {
                if (!mensajeExistente) {
                    const mensaje = document.createElement("p");
                    mensaje.id = "sin-resultados";
                    mensaje.className = "text-center col-span-full text-gray-500";
                    mensaje.textContent = "No se encontraron restaurantes.";
                    contenedorRestaurantes.appendChild(mensaje);
                }
            } else {
                if (mensajeExistente) mensajeExistente.remove();
            }

            if (mensajeSinRestaurantes) {
                if (restaurantesVisibles === 0) {
                    mensajeSinRestaurantes.classList.remove('hidden');
                } else {
                    mensajeSinRestaurantes.classList.add('hidden');
                }
            }
        }

        switchEstatus.addEventListener('change', filtrarRestaurantes);
        buscador.addEventListener('input', filtrarRestaurantes);

        document.querySelectorAll('.btn-cambiar-estatus').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const nuevoEstatus = this.dataset.estatus;

                fetch(`/restaurante/cambiar-estatus/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            estatus: nuevoEstatus
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (nuevoEstatus == "1") {
                                this.textContent = 'Deshabilitar';
                                this.className = 'btn-cambiar-estatus bg-red-200 text-red-700 px-4 py-1 rounded text-sm mt-4';
                                this.dataset.estatus = 0;
                            } else {
                                this.textContent = 'Activar';
                                this.className = 'btn-cambiar-estatus bg-green-200 text-green-700 px-4 py-1 rounded text-sm mt-4';
                                this.dataset.estatus = 1;
                            }
                            filtrarRestaurantes();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error de red');
                    });
            });
        });
        filtrarRestaurantes();
    });

    document.addEventListener("DOMContentLoaded", function() {
        const buscadorPublico = document.getElementById("buscador-restaurantes-public");
        const contenedorPublico = document.getElementById("contenedor-restaurantes");

        if (buscadorPublico && contenedorPublico) {
            buscadorPublico.addEventListener("input", function() {
                const query = buscadorPublico.value.toLowerCase().trim();
                let visibles = 0;

                contenedorPublico.querySelectorAll('.restaurante-card').forEach(card => {
                    const nombre = card.querySelector('.nombre-restaurante').textContent.toLowerCase();
                    const visible = nombre.includes(query);
                    card.style.display = visible ? 'block' : 'none';
                    if (visible) visibles++;
                });

                // Mensaje si no hay resultados
                let mensaje = document.getElementById("sin-resultados-publico");
                if (visibles === 0) {
                    if (!mensaje) {
                        mensaje = document.createElement("p");
                        mensaje.id = "sin-resultados-publico";
                        mensaje.className = "text-center col-span-full text-gray-500 mt-4";
                        mensaje.textContent = "No se encontraron restaurantes.";
                        contenedorPublico.appendChild(mensaje);
                    }
                } else {
                    if (mensaje) mensaje.remove();
                }
            });
        }
    });
</script>