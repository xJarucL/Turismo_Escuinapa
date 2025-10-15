<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión | Turismo Escuinapa</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-white text-gray-900 font-serif">

    <x-nav />

    <section class="py-16 flex items-center justify-center">
        <div class="w-full max-w-md p-8 border border-gray-400 rounded-md shadow">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo_nav.png') }}" class="w-24 h-24">
            </div>
            <h1 class="text-2xl font-serif text-center mb-6">Inicia sesión</h1>

            @if (session('status'))
                <div class="flex items-center bg-green-100 text-green-800 text-sm px-4 py-3 rounded mb-4 border border-green-300" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2l4 -4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form action="{{ route('login.inicia') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@gmail.com" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="•••••••••••" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="6Le4HDkrAAAAAEyt4yS-lPo1guwcWsn7WKuI6O7_"></div>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded hover:bg-gray-700">Iniciar sesión</button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('recuperar_contraseña') }}" class="text-sm text-gray-600 hover:underline">¿Has olvidado la contraseña?</a>
            </div>
        </div>
    </section>
    <x-footer />

</body>

</html>
