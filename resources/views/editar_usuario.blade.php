<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-900 font-serif">

    <x-nav />

    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg p-8 border border-gray-200">
        <a href="{{route('informacion_usuario')}}" onclick=""
            class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
            title="Volver">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        <h1 class="text-2xl font-serif text-center mb-6">Editar usuario</h1>

        @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('actualizar_usuario') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="flex justify-center">
                @php
                $imgPath = $usuario->user_img ? asset('storage/' . $usuario->user_img) : asset('img/default_user_plus.jpg');
                @endphp
                <label for="user_img" class="cursor-pointer">
                    <img id="previewImg" src="{{ $imgPath }}" data-original="{{ $imgPath }}" alt="Foto de perfil" class="w-32 h-32 rounded-full object-cover border shadow">
                </label>
                <input type="file" name="user_img" id="user_img" class="hidden" accept="image/*">
            </div>

            <div>
                <label class="block font-medium">Nombre:</label>
                <input type="text" name="nombre" value="{{ $usuario->nombre }}" class="w-full mt-1 border-gray-300 py-2 px-3 bg-gray-100 rounded-md shadow-sm">
            </div>

            <div>
                <label class="block font-medium">Apellido paterno:</label>
                <input type="text" name="apaterno" value="{{ $usuario->apaterno }}" class="w-full mt-1 border-gray-300 py-2 px-3 bg-gray-100 rounded-md shadow-sm">
            </div>

            <div>
                <label class="block font-medium">Apellido materno:</label>
                <input type="text" name="amaterno" value="{{ $usuario->amaterno }}" class="w-full mt-1 border-gray-300 py-2 px-3 bg-gray-100 rounded-md shadow-sm">
            </div>

            <div class="flex justify-center">
                <div class="g-recaptcha" data-sitekey="6Le4HDkrAAAAAEyt4yS-lPo1guwcWsn7WKuI6O7_"></div>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-6 py-2 rounded-md shadow">Confirmar</button>
            </div>
        </form>
    </div>
    <div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg p-8 border border-gray-200 mb-10">

        <form method="POST" action="{{ route('actualizar.password') }}" class="space-y-4 mt-10">
            @csrf

            <h2 class="text-xl font-serif text-center mb-4">¿Desea cambiar su contraseña?</h2>

            <div>
                <label class="block font-medium">Contraseña actual:</label>
                <input type="password" name="password_anterior" id="password_anterior" placeholder="**********" class="w-full mt-1 border-gray-300 py-2 px-3 bg-gray-100 rounded-md shadow-sm">
            </div>

            <div>
                <label class="block font-medium">Nueva contraseña:</label>
                <input type="password" name="password" placeholder="**********" maxlength="10" class="w-full mt-1 border-gray-300 py-2 px-3 bg-gray-100 rounded-md shadow-sm">
            </div>

            <div>
                <label class="block font-medium">Confirmar nueva contraseña:</label>
                <input type="password" name="password_confirmation" placeholder="**********" maxlength="10" class="w-full mt-1 border-gray-300 py-2 px-3 bg-gray-100 rounded-md shadow-sm">
            </div>

            <div class="flex justify-center">
                <div class="g-recaptcha" data-sitekey="6Le4HDkrAAAAAEyt4yS-lPo1guwcWsn7WKuI6O7_"></div>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white px-6 py-2 rounded-md shadow">Cambiar contraseña</button>
            </div>
        </form>
    </div>

    <x-footer />




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userImgInput = document.getElementById('user_img');
            const previewImg = document.getElementById('previewImg');
            const originalSrc = previewImg.getAttribute('data-original');

            userImgInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            window.restoreOriginalImage = function() {
                previewImg.src = originalSrc;
                userImgInput.value = '';
            }
        });
    </script>

</body>

</html>
