<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña | Turismo Escuinapa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-white text-gray-900 font-serif">

    <x-nav />

    <section class="py-16 flex items-center justify-center">
        <div class="w-full max-w-md p-8 border border-gray-300 rounded-md shadow">
            <div class="flex justify-center">
                <img src="{{ asset('img/logo_nav.png') }}" class="w-24 h-24 mb-2">
            </div>
            <h1 class="text-2xl font-serif text-center mb-6">Restablecer Contraseña</h1>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="password" class="block text-sm font-medium">Nueva contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••••" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium">Confirmar nueva contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••••" required
                        class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
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

                <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded hover:bg-gray-700">Restablecer Contraseña</button>
            </form>
        </div>
    </section>
    <x-footer />
</body>

</html>
