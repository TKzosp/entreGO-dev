<?php $__env->startSection('title', 'Tracking'); ?>

<?php $__env->startSection('content'); ?>
<section
    class="container mx-auto p-6 min-h-[calc(100vh-5rem)] flex flex-col lg:flex-row gap-6"
    id="tracking"
>
    
    <div class="w-full lg:w-2/3 bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col overflow-hidden">
        <header class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h1 class="text-sm font-semibold text-slate-800">
                    Tracking e rotas
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Visualize rotas otimizadas, waypoints e a posição atual do veículo em tempo real.
                </p>
            </div>
        </header>

        <div id="map" class="flex-1 min-h-[420px] bg-slate-100"></div>

        
        <div id="rotaResumo" class="px-4 py-3 border-t border-slate-100 text-xs text-slate-600 hidden">
            <div class="flex flex-wrap gap-4">
                <div>
                    <span class="font-medium">Distância total: </span>
                    <span id="rotaDistancia">—</span>
                </div>
                <div>
                    <span class="font-medium">Tempo estimado: </span>
                    <span id="rotaTempo">—</span>
                </div>
                <div>
                    <span class="font-medium">Waypoints: </span>
                    <span id="rotaWaypointsQtd">—</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="w-full lg:w-1/3 space-y-4">

        
        <section class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 space-y-3">
            <header class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Rota otimizada
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Informe origem e pontos de parada para gerar uma rota otimizada.
                    </p>
                </div>
            </header>

            <form id="formRotas" class="space-y-3">
                <?php echo csrf_field(); ?>

                
                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-600">
                        Origem
                    </label>
                    <input
                        type="text"
                        name="origem"
                        id="origemInput"
                        placeholder="Ex.: Av. Paulista, 1000 - São Paulo"
                        class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                        required
                    >
                </div>

                
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-medium text-slate-600">
                            Destinos / Waypoints
                        </label>
                        <button
                            type="button"
                            id="btnAddDestino"
                            class="text-xs font-medium text-entrego-blue hover:underline"
                        >
                            + adicionar ponto
                        </button>
                    </div>

                    <div id="destinosContainer" class="space-y-2">
                        
                        <div class="flex gap-2 destino-row">
                            <input
                                type="text"
                                name="destinos[]"
                                placeholder="Ex.: Rua do Porto, 200 - Campinas"
                                class="flex-1 rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                                required
                            >
                            <button
                                type="button"
                                class="btn-remove-destino text-xs text-slate-400 hover:text-rose-500"
                                title="Remover"
                            >
                                ✕
                            </button>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400">
                        A API irá otimizar automaticamente a melhor ordem dos waypoints.
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-entrego-blue text-white text-sm font-medium px-3 py-2 shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-entrego-blue"
                >
                    <span class="material-icons text-base">route</span>
                    Gerar rota otimizada
                </button>

                <p id="rotaStatus" class="text-[11px] text-slate-400"></p>
            </form>

            
            <div id="listaWaypoints" class="mt-2 hidden">
                <p class="text-[11px] font-medium text-slate-500 mb-1">
                    Ordem dos pontos da rota:
                </p>
                <ol class="text-xs text-slate-600 space-y-1" id="listaWaypointsItens"></ol>
            </div>
        </section>

        
        <section class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 space-y-3">
            <header class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Rastreamento em tempo real
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Acompanhe a posição atual do veículo para uma rota específica.
                    </p>
                </div>
            </header>

            <div class="space-y-2">
                <label class="text-xs font-medium text-slate-600">
                    ID da rota
                </label>
                <input
                    type="number"
                    id="rotaIdTracking"
                    placeholder="Ex.: 12"
                    class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                >
                <p class="text-[11px] text-slate-400">
                    Esse ID deve corresponder ao <code>rota_id</code> usado no backend
                    ao salvar em <code>Rastreamento::create()</code>.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="btnIniciarRastreamento"
                    class="flex-1 inline-flex justify-center items-center gap-2 rounded-lg bg-emerald-500 text-white text-sm font-medium px-3 py-2 shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-emerald-500"
                >
                    <span class="material-icons text-base">play_arrow</span>
                    Iniciar
                </button>

                <button
                    type="button"
                    id="btnPararRastreamento"
                    class="inline-flex justify-center items-center gap-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-medium px-3 py-2 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-slate-300"
                >
                    <span class="material-icons text-base">stop</span>
                    Parar
                </button>
            </div>

            <div class="text-[11px] text-slate-500 space-y-1">
                <p>
                    O frontend fará requisições periódicas para o endpoint de posição atual
                    da rota (ex.: <code>/tracking/rotas/{rotaId}/posicao-atual</code>),
                    consumindo os dados salvos via
                    <code>Rastreamento::create()</code>.
                </p>
                <p id="trackingStatus" class="font-medium text-slate-500"></p>
            </div>
        </section>

        
        <section class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 space-y-3">
            <header>
                <h2 class="text-sm font-semibold text-slate-800">
                    Agendar coleta
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Registre um novo pedido de coleta informando endereços, data e detalhes.
                </p>
            </header>

            
            <form
    method="POST"
    action="<?php echo e(route('pedidos.store')); ?>"
    class="space-y-3"
>

                <?php echo csrf_field(); ?>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-600">
                        Endereço de coleta (ID)
                    </label>
                    <input
                        type="number"
                        name="enderecoColeta"
                        placeholder="ID do endereço de coleta"
                        class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                        required
                    >
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-600">
                        Endereço de entrega (ID)
                    </label>
                    <input
                        type="number"
                        name="enderecoEntrega"
                        placeholder="ID do endereço de entrega"
                        class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                        required
                    >
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-600">
                        Data da coleta
                    </label>
                    <input
                        type="datetime-local"
                        name="dataColeta"
                        class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                        required
                    >
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-medium text-slate-600">
                        Descrição
                    </label>
                    <textarea
                        name="descricao"
                        rows="2"
                        placeholder="Descreva a carga, observações de coleta, restrições, etc."
                        class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue resize-none"
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-600">
                            Peso (kg)
                        </label>
                        <input
                            type="number"
                            step="0.1"
                            name="peso"
                            class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                            required
                        >
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-600">
                            Volume (m³)
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            name="volume"
                            class="w-full rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                            required
                        >
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-lg bg-entrego-blue text-white text-sm font-medium px-3 py-2 shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-entrego-blue"
                >
                    <span class="material-icons text-base">event</span>
                    Agendar coleta
                </button>
            </form>
        </section>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>&libraries=places,geometry"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // MAPA BASE
            let map;
            let rotaPolyline = null;
            let rotaBounds = null;
            let trackingMarker = null;
            let trackingInterval = null;

            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: -23.55052, lng: -46.633308 }, // SP como default
                    zoom: 12,
                    mapTypeControl: false,
                    streetViewControl: false,
                });
            }

            initMap();

            // ==============================
            // RF04 / RF10 – GERAR ROTA
            // ==============================
            const formRotas = document.getElementById('formRotas');
            const destinosContainer = document.getElementById('destinosContainer');
            const btnAddDestino = document.getElementById('btnAddDestino');
            const rotaStatus = document.getElementById('rotaStatus');
            const rotaResumo = document.getElementById('rotaResumo');
            const rotaDistanciaEl = document.getElementById('rotaDistancia');
            const rotaTempoEl = document.getElementById('rotaTempo');
            const rotaWaypointsQtdEl = document.getElementById('rotaWaypointsQtd');
            const listaWaypoints = document.getElementById('listaWaypoints');
            const listaWaypointsItens = document.getElementById('listaWaypointsItens');

            // adicionar linha de destino
            function addDestinoRow(value = '') {
                const row = document.createElement('div');
                row.className = 'flex gap-2 destino-row';

                row.innerHTML = `
                    <input
                        type="text"
                        name="destinos[]"
                        placeholder="Ex.: Rua do Porto, 200 - Campinas"
                        class="flex-1 rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue"
                        required
                        value="${value}"
                    >
                    <button
                        type="button"
                        class="btn-remove-destino text-xs text-slate-400 hover:text-rose-500"
                        title="Remover"
                    >
                        ✕
                    </button>
                `;
                destinosContainer.appendChild(row);
            }

            // Caso queira começar com duas linhas:
            if (destinosContainer.querySelectorAll('.destino-row').length === 0) {
                addDestinoRow();
            }

            btnAddDestino.addEventListener('click', () => {
                addDestinoRow();
            });

            destinosContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-remove-destino')) {
                    const rows = destinosContainer.querySelectorAll('.destino-row');
                    if (rows.length > 1) {
                        e.target.closest('.destino-row').remove();
                    }
                }
            });

            formRotas.addEventListener('submit', async (e) => {
                e.preventDefault();
                rotaStatus.textContent = 'Gerando rota otimizada...';
                rotaStatus.classList.remove('text-rose-500');
                rotaStatus.classList.add('text-slate-400');

                const origem = document.getElementById('origemInput').value.trim();
                const destinosInputs = Array.from(
                    destinosContainer.querySelectorAll('input[name="destinos[]"]')
                );
                const destinos = destinosInputs
                    .map(i => i.value.trim())
                    .filter(v => v.length > 0);

                if (!origem || destinos.length === 0) {
                    rotaStatus.textContent = 'Informe uma origem e pelo menos um destino.';
                    rotaStatus.classList.remove('text-slate-400');
                    rotaStatus.classList.add('text-rose-500');
                    return;
                }

                try {
                    const response = await fetch('<?php echo e(route('tracking.otimizar') ?? '#'); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            origem,
                            destinos
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao gerar rota.');
                    }

                    const data = await response.json();

                    // Espera que o backend retorne o JSON da Directions API em "data"
                    if (!data.routes || !data.routes.length) {
                        throw new Error('Nenhuma rota encontrada para os endereços informados.');
                    }

                    const route = data.routes[0];

                    // Limpar polyline anterior
                    if (rotaPolyline) {
                        rotaPolyline.setMap(null);
                    }

                    // Decodificar polyline
                    const points = google.maps.geometry.encoding.decodePath(
                        route.overview_polyline.points
                    );

                    rotaPolyline = new google.maps.Polyline({
                        path: points,
                        map: map,
                        strokeOpacity: 0.9,
                        strokeWeight: 5
                    });

                    // Ajustar bounds
                    rotaBounds = new google.maps.LatLngBounds();
                    points.forEach(p => rotaBounds.extend(p));
                    map.fitBounds(rotaBounds);

                    // Resumo (distância + duração)
                    let totalDistance = 0;
                    let totalDuration = 0;
                    route.legs.forEach(leg => {
                        totalDistance += leg.distance.value; // em metros
                        totalDuration += leg.duration.value; // em segundos
                    });

                    const km = (totalDistance / 1000).toFixed(1);
                    const horas = Math.floor(totalDuration / 3600);
                    const minutos = Math.round((totalDuration % 3600) / 60);

                    rotaDistanciaEl.textContent = `${km} km`;
                    rotaTempoEl.textContent = horas > 0
                        ? `${horas}h ${minutos}min`
                        : `${minutos} min`;
                    rotaWaypointsQtdEl.textContent = route.legs.length - 1; // destinos intermediários

                    rotaResumo.classList.remove('hidden');

                    // Lista de waypoints em ordem
                    listaWaypointsItens.innerHTML = '';
                    const waypointOrder = route.waypoint_order || [];
                    destinos.forEach((enderecoOriginal, indexOriginal) => {
                        const ordemOtimizada = waypointOrder.indexOf(indexOriginal);
                        listaWaypointsItens.innerHTML += `
                            <li>
                                <span class="font-semibold mr-1">${ordemOtimizada + 1}.</span>
                                ${enderecoOriginal}
                            </li>
                        `;
                    });
                    if (destinos.length) {
                        listaWaypoints.classList.remove('hidden');
                    }

                    rotaStatus.textContent = 'Rota gerada com sucesso.';
                } catch (err) {
                    rotaStatus.textContent = err.message || 'Erro ao gerar rota.';
                    rotaStatus.classList.remove('text-slate-400');
                    rotaStatus.classList.add('text-rose-500');
                }
            });

            // ==============================
            // RF05 – RASTREAMENTO EM TEMPO REAL
            // ==============================
            const rotaIdTrackingInput = document.getElementById('rotaIdTracking');
            const btnIniciarRastreamento = document.getElementById('btnIniciarRastreamento');
            const btnPararRastreamento = document.getElementById('btnPararRastreamento');
            const trackingStatus = document.getElementById('trackingStatus');

            btnIniciarRastreamento.addEventListener('click', () => {
                const rotaId = rotaIdTrackingInput.value.trim();
                if (!rotaId) {
                    trackingStatus.textContent = 'Informe o ID da rota para iniciar o rastreamento.';
                    trackingStatus.classList.add('text-rose-500');
                    return;
                }

                trackingStatus.classList.remove('text-rose-500');
                trackingStatus.textContent = `Rastreamento iniciado para a rota #${rotaId}. Atualizando posição a cada 5 segundos.`;

                if (trackingInterval) {
                    clearInterval(trackingInterval);
                }

                trackingInterval = setInterval(async () => {
                    try {
                        // Ajuste este endpoint para o que você criar no backend
                        const resp = await fetch(`/tracking/rotas/${rotaId}/posicao-atual`, {
                            headers: { 'Accept': 'application/json' }
                        });

                        if (!resp.ok) return;

                        const info = await resp.json();
                        if (!info || typeof info.latitude === 'undefined' || typeof info.longitude === 'undefined') {
                            return;
                        }

                        const lat = parseFloat(info.latitude);
                        const lng = parseFloat(info.longitude);
                        if (Number.isNaN(lat) || Number.isNaN(lng)) return;

                        const pos = { lat, lng };

                        if (!trackingMarker) {
                            trackingMarker = new google.maps.Marker({
                                position: pos,
                                map: map,
                                title: `Rota #${rotaId}`
                            });
                        } else {
                            trackingMarker.setPosition(pos);
                        }

                        map.panTo(pos);
                    } catch (e) {
                        console.error(e);
                    }
                }, 5000);
            });

            btnPararRastreamento.addEventListener('click', () => {
                if (trackingInterval) {
                    clearInterval(trackingInterval);
                    trackingInterval = null;
                }
                trackingStatus.textContent = 'Rastreamento pausado.';
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rafae\Downloads\cópia entrego\resources\views/tracking.blade.php ENDPATH**/ ?>