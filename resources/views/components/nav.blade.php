<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nav Turismo Escuinapa</title>
<link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
@auth
    @if(auth()->user()->fk_tipo_usuario == '1')
        <x-nav-admin />
    @elseif(auth()->user()->fk_tipo_usuario == '2')
        <x-nav-ayuntamiento />
    @else
        <x-nav-general />
    @endif
@endauth

@guest
    <x-nav-general />
@endguest


</body>
</html>
