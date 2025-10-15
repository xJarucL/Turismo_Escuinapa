<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar hotel | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
  <x-nav />

  <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200 mb-10">
    <a href="{{route('informacion_hotel')}}" onclick=""
      class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
      title="Volver">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </a>

    <h1 class="text-2xl font-serif mb-6 text-center">Editar Hotel</h1>

    <form method="POST" action="{{ route('editando_hotel') }}" enctype="multipart/form-data" class="space-y-5">
      @csrf
      @method('PUT')

      @if ($errors->any())
      <div class="bg-red-100 text-red-800 p-4 rounded">
        <ul>
          @foreach ($errors->all() as $error)
          <li>• {{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <div>
        <label class="block font-medium mb-1">Nombre del hotel:</label>
        <input type="text" name="nom_hotel" value="{{ $hotel->nom_hotel }}" placeholder="Ej. Hotel Playa" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="flex justify-center">
        @php
        $imgPath = $hotel->img_hotel ? asset('storage/' . $hotel->img_hotel) : asset('img/default.png');
        @endphp
        <label for="img_hotel" class="cursor-pointer">
          <img id="previewHotelImg" src="{{ $imgPath }}" data-original="{{ $imgPath }}" alt="Imagen del hotel"
            class="w-48 h-48 object-cover rounded shadow border">
        </label>
        <input type="file" name="img_hotel" id="img_hotel" class="hidden" accept="image/*">
      </div>
      <p class="font-serif text-xs text-gray-500 text-center mt-2">Haz clic en la imagen para seleccionar una nueva imagen del hotel.</p>


      <div>
        <label class="block font-medium mb-1">Dirección:</label>
        <input name="direccion" value="{{ $hotel->direccion }}" type="text" placeholder="Dirección del hotel" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Contacto del hotel:</label>
        <input type="text" name="contacto" value="{{ $hotel->contacto }}" placeholder="6951234567" required maxlength="10" pattern="\d{10}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Descripción del hotel:</label>
        <textarea name="descripcion" rows="4" placeholder="Descripción breve del hotel" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $hotel->descripcion }}</textarea>
      </div>

      <div>
        <label class="block font-medium mb-1">Enlace a Google Maps:</label>
        <input type="url" name="link_hotel" value="{{ $hotel->link_hotel }}" placeholder="https://maps.google.com/..."
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="text-center pt-4">
        <button type="submit"
          class="bg-gray-900 text-white font-medium py-2 px-6 rounded-md shadow hover:bg-gray-700">Guardar
          cambios</button>
      </div>
    </form>
  </div>

    <x-footer />

</body>

</html>
<script>
  document.getElementById('img_hotel').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const preview = document.getElementById('previewHotelImg');
      preview.src = URL.createObjectURL(file);
    }
  });
</script>
