<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Calendario | Turismo Escuinapa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="{{ asset('img/logo_nav.png') }}" type="image/png" />
  <style>
    .calendar-day {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      word-break: break-word;
      white-space: normal;
      min-height: 90px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      padding: 0.75rem;
      border-radius: 0.5rem;
      background-color: white;
      cursor: pointer;
    }

    .calendar-day:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
      z-index: 10;
    }

    .event-label {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      font-size: 0.75rem;
      margin-top: 0.25rem;
      padding: 0 0.25rem;
      border-radius: 0.25rem;
    }

    .calendar-day>span:first-child {
      white-space: nowrap;
      font-weight: 700;
      font-size: 1.125rem;
      margin-bottom: 0.25rem;
    }

    #calendar {
      overflow-x: auto;
    }

    @media (max-width: 640px) {
      #calendar {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 0.5rem !important;
      }
    }

    @media (min-width: 641px) {
      #calendar {
        display: grid !important;
        grid-template-columns: repeat(7, minmax(0, 1fr)) !important;
        gap: 1rem !important;
      }
    }

    button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
  </style>
</head>

<body class="flex flex-col min-h-screen bg-white text-gray-900 font-serif">

  <x-nav />

  <main class="flex-grow px-6 py-10 w-full max-w-7xl mx-auto">
    <div class="flex justify-between mb-6">
      <a href="{{ route('lista_eventos') }}"
        class="inline-flex items-center justify-center w-10 h-10 bg-red-600 hover:bg-red-500 text-white rounded-full shadow"
        title="Volver">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </a>
    </div>

    <div class="flex justify-center items-center mb-6">
      <h1 class="text-3xl font-serif">CALENDARIO DE EVENTOS</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-4xl mx-auto">
      <div class="flex flex-wrap gap-4 items-center justify-center mb-4">
        <button id="prevBtn"
          class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">«
          Anterior</button>

        <div class="flex gap-2 items-center">
          <select id="mesSelector" class="px-2 py-1 border rounded bg-white text-gray-800"></select>
          <select id="anioSelector" class="px-2 py-1 border rounded bg-white text-gray-800"></select>
        </div>

        <button id="nextBtn"
          class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed">Siguiente
          »</button>
      </div>

      <h2 id="calendar-title" class="text-3xl font-serif text-center mb-4"></h2>
      <div id="calendar" class="grid grid-cols-7 gap-4 text-center text-lg"></div>
    </div>
  </main>

    <x-footer />


  <!-- Modal de selección de evento -->
  <div id="modalSeleccion" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
      <h2 class="text-xl font-serif mb-4">Selecciona un evento</h2>
      <ul id="listaEventos" class="space-y-2 max-h-60 overflow-auto"></ul>
      <div class="mt-6 text-right">
        <button id="cerrarModal"
          class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded text-gray-800 font-serif">Cancelar</button>
      </div>
    </div>
  </div>

  <!-- Script -->
  <script>
    const calendarTitle = document.getElementById('calendar-title');
    const calendar = document.getElementById('calendar');
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    const rutaInformacionEvento = "{{ route('informacion_evento') }}";
    const rutaSeleccionarEvento = "{{ route('evento.seleccionar') }}";
    const csrfToken = "{{ csrf_token() }}";
    const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

    let eventos = @json($eventos);
    let eventosAnuales = @json($eventosAnuales);
    let mesesConEventos = @json($mesesConEventos);
    let cargandoCalendario = false;

    function setCargaCalendario(enCurso) {
      cargandoCalendario = enCurso;
      document.getElementById('prevBtn').disabled = enCurso;
      document.getElementById('nextBtn').disabled = enCurso;
    }

    async function fetchEventos(year, month) {
      try {
        const response = await fetch(`/api/eventos/${year}/${month + 1}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) throw new Error('Error al cargar eventos');
        return await response.json();
      } catch (error) {
        console.error(error);
        return { eventos: [], eventosAnuales: [] };
      }
    }

    async function crearCalendario(year, month) {
      if (cargandoCalendario) return;
      setCargaCalendario(true);
      calendar.innerHTML = '';
      calendarTitle.textContent = `${new Date(year, month).toLocaleString('es-ES', { month: 'long' }).toUpperCase()} ${year}`;

      daysOfWeek.forEach(day => {
        const dayHeader = document.createElement('div');
        dayHeader.className = 'font-serif text-gray-600';
        dayHeader.textContent = day;
        calendar.appendChild(dayHeader);
      });

      const firstDay = new Date(year, month, 1).getDay();
      const numDays = new Date(year, month + 1, 0).getDate();

      for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'p-4';
        calendar.appendChild(emptyCell);
      }

      if (!mesesConEventos.includes(month + 1)) {
        eventos = [];
        eventosAnuales = [];
      }

      try {
        const response = await fetchEventos(year, month);
        eventos = response.eventos;
        eventosAnuales = response.eventosAnuales;
      } catch (e) {
        console.error('Error al obtener eventos:', e);
        setCargaCalendario(false);
        return;
      }

      const hoy = new Date();

      for (let day = 1; day <= numDays; day++) {
        const dayElement = document.createElement('div');
        const esHoy = day === hoy.getDate() && month === hoy.getMonth() && year === hoy.getFullYear();

        dayElement.className =
          'calendar-day ' + (esHoy ? 'bg-blue-600 text-white font-serif shadow-lg' : 'hover:bg-blue-100 bg-white');

        const dayNumber = document.createElement('span');
        dayNumber.textContent = day;
        dayElement.appendChild(dayNumber);

        const fechaActual = new Date(year, month, day);
        const fechaYMD = fechaActual.toISOString().split('T')[0];

        const eventosDelDia = eventos.filter(ev => {
          const eventoFecha = new Date(ev.fecha_hora).toISOString().split('T')[0];
          return eventoFecha === fechaYMD;
        });

        const eventosAnualesDelDia = eventosAnuales.filter(ev => {
          if (!ev.mes_dia) return false;
          const [dd, mm] = ev.mes_dia.split('-');
          return `${mm}-${dd}` === fechaYMD.slice(5);
        });

        const tieneEventos = eventosDelDia.length > 0 || eventosAnualesDelDia.length > 0;

        if (tieneEventos) {
          dayElement.classList.add('font-serif');
        }

        function crearTarjetaEvento(ev, colorFondo) {
            const urlImagen = `/storage/${ev.img_promocional}`;

            const eventoContainer = document.createElement('div');
            eventoContainer.className = `
                relative w-full h-32 rounded-xl overflow-hidden shadow-md group
                cursor-pointer transition-transform transform hover:scale-105
            `;

            const fondo = document.createElement('div');
            fondo.className = `
                absolute inset-0 bg-cover bg-center brightness-75 group-hover:brightness-90 transition-all
            `;
            fondo.style.backgroundImage = `url(${urlImagen})`;

            const texto = document.createElement('div');
            texto.className = `
                absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white
                text-sm md:text-base font-semibold px-3 py-2 text-center
            `;
            texto.textContent = ev.nom_evento;

            eventoContainer.appendChild(fondo);
            eventoContainer.appendChild(texto);

            return eventoContainer;
        }

        eventosDelDia.forEach(ev => {
            const tarjeta = crearTarjetaEvento(ev, 'bg-yellow-200');
            dayElement.appendChild(tarjeta);
        });

        eventosAnualesDelDia.forEach(ev => {
            const tarjeta = crearTarjetaEvento(ev, 'bg-green-200');
            dayElement.appendChild(tarjeta);
        });


        if (tieneEventos) {
          dayElement.addEventListener('click', () => {
            const opciones = [
              ...eventosDelDia.map(ev => ({ id: ev.pk_evento, nombre: ev.nom_evento, tipo: 'normal' })),
              ...eventosAnualesDelDia.map(ev => ({ id: ev.pk_evento_anual, nombre: ev.nom_evento, tipo: 'anual' }))
            ];
            if (opciones.length === 1) {
              seleccionarEvento.call({ dataset: { id: opciones[0].id, tipo: opciones[0].tipo } }, new Event('click'));
            } else {
              abrirModal(opciones);
            }
          });
        }

        calendar.appendChild(dayElement);
      }

      setCargaCalendario(false);
    }

    function seleccionarEvento(e) {
      e.preventDefault();
      const eventoId = this.dataset.id;
      const tipoEvento = this.dataset.tipo;
      fetch(rutaSeleccionarEvento, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ pk_evento: eventoId, tipo: tipoEvento }),
        credentials: 'same-origin'
      }).then(response => {
        if (response.ok) {
          setTimeout(() => {
            window.location.href = rutaInformacionEvento;
          }, 100);
        } else {
          alert("Error al seleccionar evento.");
        }
      }).catch(err => {
        console.error("Error:", err);
        alert("Error al comunicarse con el servidor.");
      });
    }

    // Modal
    const modal = document.getElementById('modalSeleccion');
    const listaEventos = document.getElementById('listaEventos');
    const cerrarModalBtn = document.getElementById('cerrarModal');

    function abrirModal(opciones) {
      listaEventos.innerHTML = '';
      opciones.forEach(ev => {
        const btn = document.createElement('button');
        btn.className = 'w-full text-left px-4 py-2 bg-blue-100 hover:bg-blue-200 rounded text-sm font-medium text-blue-800';
        btn.textContent = ev.nombre;
        btn.addEventListener('click', () => {
          cerrarModal();
          seleccionarEvento.call({ dataset: { id: ev.id, tipo: ev.tipo } }, new Event('click'));
        });
        listaEventos.appendChild(btn);
      });
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function cerrarModal() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    cerrarModalBtn.addEventListener('click', cerrarModal);

    // Inicializar calendario
    crearCalendario(currentYear, currentMonth);

    // Selects de mes y año
    const mesSelector = document.getElementById('mesSelector');
    const anioSelector = document.getElementById('anioSelector');

    const meses = [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    meses.forEach((mes, index) => {
      const option = document.createElement('option');
      option.value = index;
      option.textContent = mes;
      mesSelector.appendChild(option);
    });

    const anioActual = new Date().getFullYear();
    for (let i = anioActual - 2; i <= anioActual + 4; i++) {
      const option = document.createElement('option');
      option.value = i;
      option.textContent = i;
      anioSelector.appendChild(option);
    }

    mesSelector.value = currentMonth;
    anioSelector.value = currentYear;

    mesSelector.addEventListener('change', () => {
      currentMonth = parseInt(mesSelector.value);
      crearCalendario(currentYear, currentMonth);
    });

    anioSelector.addEventListener('change', () => {
      currentYear = parseInt(anioSelector.value);
      crearCalendario(currentYear, currentMonth);
    });

    // Actualizar selects al cambiar con botones
    document.getElementById('prevBtn').addEventListener('click', () => {
      currentMonth--;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
      }
      mesSelector.value = currentMonth;
      anioSelector.value = currentYear;
      crearCalendario(currentYear, currentMonth);
    });

    document.getElementById('nextBtn').addEventListener('click', () => {
      currentMonth++;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
      }
      mesSelector.value = currentMonth;
      anioSelector.value = currentYear;
      crearCalendario(currentYear, currentMonth);
    });
  </script>
</body>

</html>
