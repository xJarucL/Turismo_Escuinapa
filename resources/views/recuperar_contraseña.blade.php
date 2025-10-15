<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Turismo Escuinapa</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-white text-gray-900 font-serif">
    <x-nav />

    <section class="py-16 flex items-center justify-center">
        <div class="w-full max-w-md p-8 border border-gray-300 rounded-md shadow bg-white">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo_nav.png') }}" class="w-24 h-24" alt="Logo Turismo Escuinapa">
            </div>

            <h1 class="text-2xl font-serif text-center mb-6">Recuperar Contraseña</h1>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium">Correo electrónico</label>
                    <input type="email" name="email" id="email" placeholder="ejemplo@gmail.com" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="6LfT_fsqAAAAAJn4Sm2Vi4p1GDgaZekOPxrcjJMA"></div>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded hover:bg-gray-700">
                    Enviar instrucciones
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">¿Recordaste tu contraseña? Inicia sesión</a>
            </div>
        </div>
    </section>
    <x-footer />

</body>

</html>
