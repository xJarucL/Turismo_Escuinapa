<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar presidente | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png">
</head>

<body class="bg-gray-100 text-gray-800 font-serif">
  <x-nav />

  <div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-lg shadow-md border border-gray-200 mb-10">
    <a href="{{route('informacion_presidente')}}" onclick=""
            class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
            title="Volver">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

    <h1 class="text-2xl font-serif mb-6 text-center">Editar presidente</h1>

    <form method="POST" action="{{ route('editando_presidente') }}" enctype="multipart/form-data" class="space-y-5">
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
        <label class="block font-medium mb-1">Nombre completo del presidente:</label>
        <input type="text" name="nombre" value="{{ $presidente->nombre }}" placeholder="Nombre completo del presidente" required
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="flex justify-center">
        @php
            $imgPath = $presidente->img_presidente ? asset('storage/' . $presidente->img_presidente) : asset('img/default.png');
        @endphp
        <label for="img_presidente" class="cursor-pointer">
            <img id="previewPresidenteImg" src="{{ $imgPath }}" data-original="{{ $imgPath }}" alt="Imagen del presidente"
                class="w-48 h-48 object-cover rounded shadow border">
        </label>
        <input type="file" name="img_presidente" id="img_presidente" class="hidden" accept="image/*">
    </div>
    <p class="font-serif text-xs text-gray-500 text-center mt-2">Haz clic en la imagen para seleccionar una nueva foto del presidente.</p>


      <div>
        <label class="block font-medium mb-1">Fecha de incio de presidencia:</label>
        <input type="date" name="fec_inicio" value="{{ $presidente->fec_inicio ? $presidente->fec_inicio->format('Y-m-d') : '' }}"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Fecha de finalización de presidencia:</label>
        <input type="date" name="fec_fin" value="{{ $presidente->fec_fin ? $presidente->fec_fin->format('Y-m-d') : '' }}"
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>

      <div>
        <label class="block font-medium mb-1">Descripción del presidente:</label>
        <textarea rows="4" name="descripcion" required placeholder="Contexto político."
          class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{$presidente -> descripcion}}</textarea>
      </div>

      <div  class="text-center pt-4">
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
  document.getElementById('img_presidente').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('previewPresidenteImg');
            preview.src = URL.createObjectURL(file);
        }
    });


</script>
