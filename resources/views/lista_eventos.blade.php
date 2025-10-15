<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventos culturales | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="flex flex-col min-h-screen bg-white text-gray-900 font-serif">

    <x-nav />

    @auth

    <main class="flex-grow px-4 py-10 w-full">
        <div>
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
                <a href="{{ route('registro_evento') }}"
                    class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                    Registrar evento culturales
                </a>
            </div>

            <div class="flex justify-center items-center mb-6">
                <h1 class="text-3xl font-serif">Eventos culturales</h1>
            </div>

            <div class="flex justify-center mb-10">
                <div class="relative w-full max-w-md">
                    <input id="buscador-eventos" type="text" placeholder="Buscar evento culturales..." class="w-full pl-4 pr-10 py-2 border border-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-red-400">
                    <span class="absolute right-3 top-2.5 text-gray-900">🔍</span>
                </div>
            </div>

            <div>
                <div class="max-w-6xl mx-auto px-6 mb-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-6">
                            <label for="switch-estatus" class="inline-flex items-center me-5 cursor-pointer">
                                <input type="checkbox" id="switch-estatus" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus700ring-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-700-600 peer-checked:bg-green-600 dark:peer-700d:bg-green-600 dot"></div>
                                <span id="toggle-label" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-500">Mostrar inactivos</span>
                            </label>
                            <label for="switch-eventos-anuales" class="inline-flex items-center me-5 cursor-pointer">
                                <input type="checkbox" id="switch-eventos-anuales" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus700ring-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-700-600 peer-checked:bg-green-600 dark:peer-700d:bg-green-600 dot"></div>
                                <span id="toggle-label-anual" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-500">Mostrar eventos culturales anuales</span>
                            </label>
                        </div>
                        <div class="w-full md:w-auto text-center md:text-right">
                            <a href="{{ route('calendario_eventos') }}"
                                class="inline-flex items-center justify-center gap-2 w-full md:w-auto bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm shadow transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10m-11 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                <span>Calendario de eventos culturales</span>
                            </a>
                        </div>
                    </div>
                </div>



                <div id="contenedor-eventos-normales" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-7xl mx-auto px-4"> @forelse ($eventos as $evento)
                    <div class="evento-card border rounded-lg p-3 text-center shadow-xs transition-all duration-300 hover:shadow-md hover:-translate-y-1 mx-auto w-full max-w-[280px]">
                        <div class="bg-gray-100 rounded-md aspect-square mb-2 overflow-hidden">
                            <a href="{{route('informacion_evento')}}" class="enlace-evento block w-full h-full" data-id="{{ $evento->pk_evento }}" data-tipo="normal">
                                <img src="{{ asset('storage/' . $evento->img_promocional) }}"
                                    alt="Evento"
                                    class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105" />
                            </a>
                        </div>
                        <a href="{{route('informacion_evento')}}" class="enlace-evento block" data-id="{{ $evento->pk_evento }}" data-tipo="normal">
                            <h3 class="font-medium text-lg nombre-evento hover:text-red-600 transition-colors duration-300 mb-0.5 line-clamp-1">
                                {{ $evento->nom_evento }}
                            </h3>
                        </a>
                        <p class="font-serif text-xs text-gray-500 mb-1.5 line-clamp-2">
                            {{ \Illuminate\Support\Str::limit($evento->descripcion, 25, '...') }}
                        </p>

                        @if ($evento->estatus == 1)
                        <button
                            class="btn-cambiar-estatus bg-red-200 text-red-700 px-4 py-1 rounded text-sm mt-4 transition-all hover:-translate-y-1 hover:shadow-md active:translate-y-0"
                            data-id="{{ $evento->pk_evento }}"
                            data-estatus="0"
                            data-tipo="normal">Deshabilitar</button>
                        @else
                        <button
                            class="btn-cambiar-estatus bg-green-200 text-green-700 px-4 py-1 rounded text-sm mt-4 transition-all hover:-translate-y-1 hover:shadow-md active:translate-y-0"
                            data-id="{{ $evento->pk_evento }}"
                            data-estatus="1"
                            data-tipo="normal">Activar</button>
                        @endif
                    </div>
                    @empty
                    <p class="font-serif text-center col-span-full text-gray-500 py-10 w-full flex justify-center items-center">
                        No hay eventos culturales registrados.
                    </p>
                    @endforelse

                </div>

                <!-- Eventos anuales -->

                <div>
                    <div style="display: none;" id="contenedor-eventos-anuales" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-7xl mx-auto px-4"> @forelse ($eventosAnuales as $eventoAnual)
                        <div class="evento-card border rounded-lg p-3 text-center shadow-xs transition-all duration-300 hover:shadow-md hover:-translate-y-1 mx-auto w-full max-w-[280px]">
                            <div class="bg-gray-100 rounded-md aspect-square mb-2 overflow-hidden">
                                <a href="{{route('informacion_evento')}}" class="enlace-evento block w-full h-full" data-id="{{ $eventoAnual->pk_evento_anual }}" data-tipo="anual">
                                    <img src="{{ asset('storage/' . $eventoAnual->img_promocional) }}" alt="eventoAnual" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105" />
                                </a>
                            </div>
                            <a href="{{route('informacion_evento')}}" class="enlace-evento block" data-id="{{ $eventoAnual->pk_evento_anual }}" data-tipo="anual">
                                <h3 class="font-medium text-lg nombre-evento hover:text-red-600 transition-colors duration-300 mb-0.5 line-clamp-1">{{ $eventoAnual->nom_evento }}</h3>
                            </a>
                            <p class="font-serif text-xs text-gray-500 mb-1.5 line-clamp-2">{{ \Illuminate\Support\Str::limit($eventoAnual->descripcion, 25, '...') }}</p>

                            @if ($eventoAnual->estatus == 1)
                            <button
                                class="btn-cambiar-estatus bg-red-200 text-red-700 px-4 py-1 rounded text-sm mt-4 transition-all hover:-translate-y-1 hover:shadow-md active:translate-y-0"
                                data-id="{{ $eventoAnual->pk_evento_anual }}"
                                data-estatus="0"
                                data-tipo="anual">Deshabilitar</button>
                            @else
                            <button
                                class="btn-cambiar-estatus bg-green-200 text-green-700 px-4 py-1 rounded text-sm mt-4 transition-all hover:-translate-y-1 hover:shadow-md active:translate-y-0"
                                data-id="{{ $eventoAnual->pk_evento_anual }}"
                                data-estatus="1"
                                data-tipo="anual">Activar</button>
                            @endif
                        </div>
                        @empty
                        <p class="font-serif text-center col-span-full text-gray-500 py-10 w-full flex justify-center items-center">
                            No hay eventos culturales anuales registrados.
                        </p>
                        @endforelse
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
                                <h1 class="text-3xl font-serif">Eventos culturales</h1>
                            </div>

                            <div class="flex justify-center mb-10">
                                <div class="relative w-full sm:w-1/2">
                                    <input id="buscador-eventos" type="text" placeholder="Buscar evento culturales..."
                                        class="w-full pl-4 pr-10 py-2 border border-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-red-400">
                                    <span class="absolute right-3 top-2.5 text-gray-700">🔍</span>
                                </div>
                            </div>

                            <div class="max-w-6xl mx-auto px-6 mb-8">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div class="flex flex-wrap items-center gap-6">
                                        <label for="toggle-anual" class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" id="toggle-anual" class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus700ring-green-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-700-600 peer-checked:bg-green-600 dark:peer-700d:bg-green-600 dot"></div>
                                            <span id="toggle-label" class="ml-3 text-sm text-gray-700">Mostrar eventos culturales anuales</span>
                                        </label>
                                    </div>
                                    <div class="w-full md:w-auto text-center md:text-right">
                                        <a href="{{ route('calendario_eventos') }}"
                                            class="inline-flex items-center justify-center gap-2 w-full md:w-auto bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm shadow transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10m-11 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                            </svg>
                                            <span>Calendario de eventos culturales</span>
                                        </a>
                                    </div>
                                    <div class="w-full h-6 bg-[url('img/Lineas.png')] bg-repeat-x bg-[length:290px_auto] opacity-90 mb-6"></div>

                                </div>
                            </div>

                            <div id="contenedor-eventos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-7xl mx-auto px-6">
                                @forelse ($eventos_public as $evento)
                                <div class="evento-card border rounded-lg p-3 text-center shadow-xs transition-all duration-300 hover:shadow-md hover:-translate-y-1 mx-auto w-full max-w-[280px]">
                                    <div class="bg-gray-100 rounded-md aspect-square mb-2 overflow-hidden">
                                        <a href="{{route('informacion_evento')}}" class="enlace-evento block w-full h-full" data-id="{{ $evento->pk_evento }}" data-tipo="normal">
                                            <img src="{{ asset('storage/' . $evento->img_promocional) }}" alt="Evento" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105" />
                                        </a>
                                    </div>
                                    <a href="{{route('informacion_evento')}}" class="enlace-evento block" data-id="{{ $evento->pk_evento }}" data-tipo="normal">
                                        <h3 class="font-medium text-lg nombre-evento hover:text-red-600 transition-colors duration-300 mb-0.5 line-clamp-1">{{ $evento->nom_evento }}</h3>
                                    </a>
                                    <p class="font-serif text-xs text-gray-500 mb-1.5 line-clamp-2">{{ \Illuminate\Support\Str::limit($evento->descripcion, 25, '...') }}</p>
                                </div>
                                @empty
                                <p class="font-serif text-center col-span-full text-gray-500 py-10 w-full flex justify-center items-center">
                                    No hay eventos culturales registrados.
                                </p>
                                @endforelse
                            </div>
                            <div style="display: none;" id="contenedor-eventos-anuales" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-7xl mx-auto px-6">
                                @foreach ($eventos_anuales_public as $eventoAnual)
                                <div class="evento-card border rounded-lg p-3 text-center shadow-xs transition-all duration-300 hover:shadow-md hover:-translate-y-1 mx-auto w-full max-w-[280px]">
                                    <div class="bg-gray-100 rounded-md aspect-square mb-2 overflow-hidden">
                                        <a href="{{route('informacion_evento')}}" class="enlace-evento block w-full h-full" data-id="{{ $eventoAnual->pk_evento_anual }}" data-tipo="anual">
                                            <img src="{{ asset('storage/' . $eventoAnual->img_promocional) }}" alt="eventoAnual" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105" />
                                        </a>
                                    </div>
                                    <a href="{{route('informacion_evento')}}" class="enlace-evento block" data-id="{{ $eventoAnual->pk_evento_anual }}" data-tipo="anual">
                                        <h3 class="font-medium text-lg nombre-evento hover:text-red-600 transition-colors duration-300 mb-0.5 line-clamp-1">{{ $eventoAnual->nom_evento }}</h3>
                                    </a>
                                    <p class="font-serif text-xs text-gray-500 mb-1.5 line-clamp-2">{{ \Illuminate\Support\Str::limit($eventoAnual->descripcion, 25, '...') }}</p>
                                </div>
                                @endforeach
                                @endauth
                            </div>
                        </main>

                        <x-footer />


</body>

</html>

<script>
    // Redirigir al evento seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.enlace-evento').forEach(function(enlace) {
            enlace.addEventListener('click', function(e) {
                e.preventDefault();

                const eventoId = this.dataset.id;
                const tipoEvento = this.dataset.tipo;

                fetch("{{ route('evento.seleccionar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            pk_evento: eventoId,
                            tipo: tipoEvento
                        })
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.href = "{{ route('informacion_evento') }}";
                        } else {
                            alert("Error al seleccionar evento.");
                        }
                    })
                    .catch(() => alert("Error al comunicarse con el servidor."));
            });
        });
    });



    // Cambiar estatus, mostrar eventos activos e inactivos y buscar eventos
    document.addEventListener("DOMContentLoaded", function() {
        const csrfToken = '{{ csrf_token() }}';
        const switchEstatus = document.getElementById('switch-estatus');
        const mensajeSinEventos = document.getElementById('mensaje-sin-eventos');
        const buscador = document.getElementById("buscador-eventos");
        const contenedorEventos = document.getElementById("contenedor-eventos");


        function filtrarEventos() {
            const mostrarInactivos = switchEstatus.checked;
            const query = buscador.value.toLowerCase().trim();
            let eventosVisibles = 0;

            document.querySelectorAll('.evento-card').forEach(card => {
                const boton = card.querySelector('.btn-cambiar-estatus');
                const estatus = boton.dataset.estatus === "0" ? "1" : "0";
                const nombre = card.querySelector(".nombre-evento").textContent.toLowerCase();
                const coincideBusqueda = nombre.includes(query);

                let visible = false;

                if (mostrarInactivos && estatus === "0" && coincideBusqueda) {
                    visible = true;
                } else if (!mostrarInactivos && estatus === "1" && coincideBusqueda) {
                    visible = true;
                }

                card.style.display = visible ? 'block' : 'none';
                if (visible) eventosVisibles++;

            });


            const mensajeExistente = document.getElementById("sin-resultados");

            if (eventosVisibles === 0) {
                if (!mensajeExistente && contenedorEventos) {
                    const mensaje = document.createElement("p");
                    mensaje.id = "sin-resultados";
                    mensaje.className = "text-center col-span-full text-gray-500";
                    mensaje.textContent = "";
                    contenedorEventos.appendChild(mensaje);
                }
            } else {
                if (mensajeExistente) mensajeExistente.remove();
            }

            if (mensajeSinEventos) {
                if (eventosVisibles === 0) {
                    mensajeSinEventos.classList.remove('hidden');
                } else {
                    mensajeSinEventos.classList.add('hidden');
                }
            }
        }

        switchEstatus.addEventListener('change', filtrarEventos);
        buscador.addEventListener('input', filtrarEventos);
        const toggleLabel = document.getElementById('toggle-label');

        switchEstatus.addEventListener('change', function() {
            toggleLabel.textContent = this.checked ? 'Mostrar activos' : 'Mostrar inactivos';
            filtrarEventos();
        });


        document.querySelectorAll('.btn-cambiar-estatus').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const nuevoEstatus = this.dataset.estatus;
                const tipo = this.dataset.tipo || 'normal';
                const card = this.closest('.evento-card');

                fetch(`/evento/cambiar-estatus/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            estatus: nuevoEstatus,
                            tipo: tipo
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (nuevoEstatus == "1") {
                                this.textContent = 'Deshabilitar';
                                this.className = 'btn-cambiar-estatus bg-red-200 text-red-700 px-4 py-1 rounded text-sm mt-4';
                                this.dataset.estatus = 0;

                                card.dataset.estatus = "1";
                            } else {
                                this.textContent = 'Activar';
                                this.className = 'btn-cambiar-estatus bg-green-200 text-green-700 px-4 py-1 rounded text-sm mt-4';
                                this.dataset.estatus = 1;

                                card.dataset.estatus = "0";
                            }
                            filtrarEventos();
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
        toggleLabel.textContent = switchEstatus.checked ? 'Mostrar activos' : 'Mostrar inactivos';
        filtrarEventos();

        const switchEventosAnuales = document.getElementById('switch-eventos-anuales');
        const toggleLabelAnual = document.getElementById('toggle-label-anual');
        const contenedorNormales = document.getElementById('contenedor-eventos-normales');
        const contenedorAnuales = document.getElementById('contenedor-eventos-anuales');

        switchEventosAnuales.addEventListener('change', function() {
            if (this.checked) {
                contenedorNormales.style.display = 'none';
                contenedorAnuales.style.display = 'grid';
                toggleLabelAnual.textContent = 'Mostrar eventos normales';
            } else {
                contenedorNormales.style.display = 'grid';
                contenedorAnuales.style.display = 'none';
                toggleLabelAnual.textContent = 'Mostrar eventos anuales';
                filtrarEventos();
            }
        });
        filtrarEventos();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const buscador = document.getElementById('buscador-eventos');
        const toggleAnual = document.getElementById('toggle-anual');
        const contenedorNormales = document.getElementById('contenedor-eventos');
        const contenedorAnuales = document.getElementById('contenedor-eventos-anuales');

        function filtrarEventos() {
            const query = buscador.value.toLowerCase().trim();
            const mostrarAnuales = toggleAnual.checked;

            // Mostrar solo el contenedor correspondiente
            contenedorNormales.style.display = mostrarAnuales ? 'none' : 'grid';
            contenedorAnuales.style.display = mostrarAnuales ? 'grid' : 'none';

            const contenedorActivo = mostrarAnuales ? contenedorAnuales : contenedorNormales;

            // Filtrar los eventos dentro del contenedor activo
            let encontrados = 0;
            contenedorActivo.querySelectorAll('.evento-card').forEach(card => {
                const nombre = card.querySelector('.nombre-evento').textContent.toLowerCase();
                const visible = nombre.includes(query);
                card.style.display = visible ? 'block' : 'none';
                if (visible) encontrados++;
            });

            // Mostrar mensaje si no hay resultados
            let mensaje = contenedorActivo.querySelector('#sin-resultados');
            if (encontrados === 0 && query !== '') {
                if (!mensaje) {
                    mensaje = document.createElement('p');
                    mensaje.id = 'sin-resultados';
                    mensaje.className = 'text-center col-span-full text-gray-500 mt-4';
                    mensaje.textContent = 'No se encontraron eventos culturales con ese nombre.';
                    contenedorActivo.appendChild(mensaje);
                }
            } else {
                if (mensaje) mensaje.remove();
            }
        }

        buscador.addEventListener('input', filtrarEventos);
        toggleAnual.addEventListener('change', filtrarEventos);

        filtrarEventos();
    });
</script>