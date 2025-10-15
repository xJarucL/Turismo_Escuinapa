<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800 font-serif">

    <x-nav />
    <main class="flex-grow">

        <!-- Arturo aca puedes darle diseño al div, no se donde lo quieras colocar, ahí vele donde estaria mejor -->
        <div id="mensaje-notificacion" class="fixed top-5 right-5 z-50 hidden rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out"></div>

        <style>
            .notificacion {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: rgb(208, 208, 38);
                color: #fff;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                opacity: 0;
                transform: translateY(-20px);
                transition: opacity 0.5s ease, transform 0.5s ease;
                z-index: 9999;
                font-family: sans-serif;
                font-size: 16px;
                display: none;
            }

            .notificacion.visible {
                display: block;
                opacity: 1;
                transform: translateY(0);
            }
        </style>

        <div class="max-w-6xl mx-auto py-10 px-4">
            <div class="flex justify-between mb-6">
                <a href="{{route('administrador')}}" onclick=""
                    class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
                    title="Volver">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <a href="{{ route('registrar_usuario') }}"> <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-6 py-2 rounded-md shadow">Registrar usuario</button> </a>
            </div>

            <h1 class="text-3xl font-serif text-center mb-8">Lista de usuarios</h1>

            <div class="flex justify-center mb-10">
                <div class="relative w-full max-w-md">
                    <input id="buscador-usuarios" type="text" placeholder="Buscar usuario..." class="w-full pl-4 pr-10 py-2 border border-red-400 rounded-full focus:outline-none focus:ring-2 focus:ring-red-400">
                    <span class="absolute right-3 top-2.5 text-gray-900">🔍</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-4 mb-4 px-2">
                <div class="w-full sm:w-auto">
                    <label for="filtro-rol" class="block text-sm font-medium text-gray-700 mb-1 font-serif">Rol:</label>
                    <select id="filtro-rol" class="w-full sm:w-48 rounded-md border border-gray-300 py-2 px-3 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                    <option value="">Todos</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Ayuntamiento">Ayuntamiento</option>
                    </select>
                </div>

                <div class="w-full sm:w-auto">
                    <label for="filtro-estatus" class="block text-sm font-medium text-gray-700 mb-1 font-serif">Estatus:</label>
                    <select id="filtro-estatus" class="w-full sm:w-48 rounded-md border border-gray-300 py-2 px-3 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                    <option value="">Todos</option>
                    <option value="Activo">Activo</option>
                    <option value="Deshabilitado">Deshabilitado</option>
                    </select>
                </div>
            </div>


            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full table-auto text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-left text-xs font-serif uppercase tracking-wider">
                            <th class="px-6 py-4">Nombre completo</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Rol</th>
                            <th class="px-6 py-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-buscar">
                        @forelse ($usuarios as $usuario)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><img src="{{ $usuario->user_img ? asset('storage/' . $usuario->user_img) : asset('img/default_user.jpg') }}" class="w-8 h-8 rounded-full object-cover border shadow mr-2 inline">{{ $usuario->nombre }} {{ $usuario->apaterno }} {{ $usuario->amaterno }}</td>
                            <td class="px-6 py-4">{{ $usuario->email }}</td>
                            <td class="px-6 py-4">
                                <select class="actualizar-rol bg-white border border-gray-300 rounded px-2 py-1" data-user-id="{{ $usuario->pk_usuario }}">
                                    <option value="1" {{ $usuario->fk_tipo_usuario == 1 ? 'selected' : '' }}>Administrador</option>
                                    <option value="2" {{ $usuario->fk_tipo_usuario == 2 ? 'selected' : '' }}>Ayuntamiento</option>
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <select class="actualizar-estatus bg-white border border-gray-300 rounded px-2 py-1" data-user-id="{{ $usuario->pk_usuario }}">
                                    <option value="1" {{ $usuario->estatus == 1 ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ $usuario->estatus == 0 ? 'selected' : '' }}>Deshabilitado</option>
                                </select>
                            </td>
                        </tr>
                        @empty
                        <p class="font-serif text-center col-span-full text-gray-500 text-xs py-6">
                            No hay usuarios registrados.
                        </p>
                        @endforelse
                    </tbody>
                </table>

                <div id="mensaje-sin-resultados" class="text-center text-sm text-gray-500 py-4 hidden">
                    No se encontraron usuarios con ese criterio de búsqueda.
                </div>

            </div>
            <div class="mt-6">
                {{ $usuarios->links() }}
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        // Función para mostrar mensajes de notificación
        function mostrarMensaje(texto, tipo = 'success') {
            const div = document.getElementById('mensaje-notificacion');
            div.textContent = texto;

            // Colores según el tipo
            const colores = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                warning: 'bg-yellow-500'
            };

            div.className = `fixed top-5 right-5 z-50 rounded-lg shadow-lg px-6 py-4 text-white font-medium transition-all duration-500 ease-in-out ${colores[tipo]}`;
            div.style.display = 'block';
            div.style.opacity = '1';

            setTimeout(() => {
                div.style.opacity = '0';
                setTimeout(() => div.style.display = 'none', 500);
            }, 3000);
        }

        function Respuesta(fetchResponse, valorAnterior, select) {
            fetchResponse
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensaje(data.message);
                    } else {
                        mostrarMensaje("Error: " + data.message);
                        select.value = valorAnterior;
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    mostrarMensaje("Ocurrió un error. Intenta nuevamente.");
                    select.value = valorAnterior;
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const csrfToken = '{{ csrf_token() }}';

            // ROL
            document.querySelectorAll('.actualizar-rol').forEach(select => {
                let valorAnterior = select.value;

                select.addEventListener('change', function() {
                    const pk_usuario = this.dataset.userId;
                    const nuevoRol = this.value;

                    const peticion = fetch(`/actualizar-rol`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            pk_usuario: pk_usuario,
                            fk_tipo_usuario: nuevoRol
                        })
                    })

                    Respuesta(peticion, valorAnterior, select);

                });
            });

            // ESTATUS
            document.querySelectorAll('.actualizar-estatus').forEach(select => {
                let valorAnterior = select.value;

                select.addEventListener('change', function() {
                    const pk_usuario = this.dataset.userId;
                    const nuevoEstatus = this.value;

                    const peticion = fetch(`/actualizar-estatus`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            pk_usuario: pk_usuario,
                            estatus: nuevoEstatus
                        })
                    })

                    Respuesta(peticion, valorAnterior, select);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const buscador = document.getElementById("buscador-usuarios");
            const filtroRol = document.getElementById("filtro-rol");
            const filtroEstatus = document.getElementById("filtro-estatus");
            const filas = document.querySelectorAll("#tabla-buscar tr");
            const mensajeSinResultados = document.getElementById("mensaje-sin-resultados");

            function aplicarFiltros() {
                let filtroTexto = buscador.value.toLowerCase();
                let rolSeleccionado = filtroRol.value;
                let estatusSeleccionado = filtroEstatus.value;

                let totalVisibles = 0;

                filas.forEach(fila => {
                    const nombreEmail = fila.cells[0].textContent.toLowerCase() + fila.cells[1].textContent.toLowerCase();
                    const rol = fila.cells[2].querySelector("select").selectedOptions[0].text;
                    const estatus = fila.cells[3].querySelector("select").selectedOptions[0].text;

                    const coincideTexto = nombreEmail.includes(filtroTexto);
                    const coincideRol = rolSeleccionado === "" || rol === rolSeleccionado;
                    const coincideEstatus = estatusSeleccionado === "" || estatus === estatusSeleccionado;

                    if (coincideTexto && coincideRol && coincideEstatus) {
                        fila.style.display = "";
                        totalVisibles++;
                    } else {
                        fila.style.display = "none";
                    }
                });

                mensajeSinResultados.classList.toggle("hidden", totalVisibles > 0);
            }

            buscador.addEventListener("keyup", aplicarFiltros);
            filtroRol.addEventListener("change", aplicarFiltros);
            filtroEstatus.addEventListener("change", aplicarFiltros);
        });

    </script>

    @if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            mostrarMensaje(@json(session('success')));
        });
    </script>
    @endif
</body>

</html>
