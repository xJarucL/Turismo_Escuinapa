<style>
  @keyframes fade-slide {
    0% {
      opacity: 0;
      transform: translateY(-8px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .fade-slide {
    animation: fade-slide 0.5s ease-out both;
  }
</style>

<div class="flex flex-col md:flex-row md:space-x-6 space-y-6 md:space-y-0 mb-10">
  @if ($eventos->count() > 0)
      <div class="fade-slide bg-yellow-50 border-l-4 border-yellow-500 text-yellow-900 p-5 rounded-xl shadow-md flex-1">
          <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-yellow-600 mt-1 animate-pulse" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 11-7.485 3.418" />
              </svg>
              <div>
                  <h3 class="font-bold text-lg">¡Atención! Eventos en los próximos 14 días:</h3>
                  <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                      @foreach ($eventos as $evento)
                          <li>
                              <span class="font-semibold">{{ $evento->nom_evento }}</span>
                              el {{ \Carbon\Carbon::parse($evento->fecha_hora)->format('d M Y') }}.
                          </li>
                      @endforeach
                  </ul>
              </div>
          </div>
      </div>
  @endif

  @if ($eventosAnuales->count() > 0)
      <div class="fade-slide bg-blue-50 border-l-4 border-blue-500 text-blue-900 p-5 rounded-xl shadow-md flex-1">
          <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-blue-600 mt-1 animate-bounce" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" />
              </svg>
              <div>
                  <h3 class="font-bold text-lg">Eventos anuales próximos:</h3>
                  <ul class="list-disc list-inside mt-2 space-y-1 text-sm">
                      @foreach ($eventosAnuales as $evento)
                          <li>
                              <span class="font-semibold">{{ $evento->nom_evento }}</span>
                          </li>
                      @endforeach
                  </ul>
              </div>
          </div>
      </div>
  @endif
</div>
