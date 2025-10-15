<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar restaurante | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
  <x-nav />

  <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200 mb-10">

    <a href="{{ route('informacion_restaurante') }}" onclick="" class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow mb-4" title="Volver">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </a>

    <h1 class="text-2xl font-serif mb-6 text-center">Editar Restaurante</h1>

    <form method="POST" action="{{ route('editando_restaurante') }}" enctype="multipart/form-data" class="space-y-5">
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
        <label class="block font-medium mb-1">Nombre del restaurante:</label>
        <input type="text" name="nom_restaurante" value="{{ $restaurante->nom_restaurante }}" placeholder="Ej. Restaurante El Sabor" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div class="flex justify-center">
          @php
              $imgPathRest = $restaurante->img_promocional ? asset('storage/' . $restaurante->img_promocional) : asset('img/default.png');
          @endphp
          <label for="img_promocional" class="cursor-pointer">
              <img id="previewRestaurantImg" src="{{ $imgPathRest }}" data-original="{{ $imgPathRest }}" alt="Imagen promocional del restaurante"
                  class="w-48 h-48 object-cover rounded shadow border">
          </label>
          <input type="file" name="img_promocional" id="img_promocional" class="hidden" accept="image/*">
      </div>
      <p class="font-serif text-xs text-gray-500 text-center mt-2">Haz clic en la imagen para seleccionar una nueva imagen promocional del restaurante.</p>


      <div>
        <label class="block font-medium mb-1">Dirección:</label>
        <input type="text" name="direccion" value="{{ $restaurante->direccion }}" placeholder="Dirección del restaurante" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-medium mb-1">Hora de apertura:</label>
          <input type="time"name="hora_apertura" value="{{ \Carbon\Carbon::parse($restaurante->hora_apertura)->format('H:i') }}" required
            class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
          <label class="block font-medium mb-1">Hora de cierre:</label>
          <input type="time" name="hora_cierre" value="{{ \Carbon\Carbon::parse($restaurante->hora_cierre)->format('H:i') }}" required
            class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
      </div>

      <div>
        <label class="block font-medium mb-1">Teléfono de contacto:</label>
        <input type="tel" name="tel_restaurante" value="{{ $restaurante->tel_restaurante }}" placeholder="Ej. 6951234567" required maxlength="10" pattern="\d{10}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div>
        <label class="block font-medium mb-1">Correo electrónico:</label>
        <input type="email" name="email_restaurante" value="{{ $restaurante->email_restaurante }}"  placeholder="correo@ejemplo.com" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <div>
        <label class="block font-medium mb-1">Descripción del restaurante:</label>
        <textarea rows="4" name="descripcion" placeholder="Describe tu restaurante aquí" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $restaurante->descripcion }}</textarea>
      </div>

      <div>
        <label class="block font-medium mb-1">URL de reseña en Google (Opcional):</label>
        <input type="url" name="url_google_reseña" value="{{ $restaurante->url_google_reseña }}" placeholder="https://maps.google.com/..."
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
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
  document.getElementById('img_promocional').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const preview = document.getElementById('previewRestaurantImg');
        preview.src = URL.createObjectURL(file);
    }
});
</script>
