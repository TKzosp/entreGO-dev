@extends('layouts.app')

@section('title', 'Tracking')

@section('content')
<section
    class="container mx-auto p-6 min-h-[calc(100vh-5rem)] flex flex-col lg:flex-row gap-6"
    id="tracking"
>
    {{-- MAPA E ROTAS (RF04 / RF10 / RF05 visual) --}}
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

        {{-- Resumo da rota gerada --}}
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

    {{-- PAINEL LATERAL: ROTAS, RASTREAMENTO, AGENDAMENTO --}}
    <div class="w-full lg:w-1/3 space-y-4">

        {{-- RF04 + RF10 – GERAR ROTAS OTIMIZADAS / GERENCIAR WAYPOINTS --}}
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
                @csrf

                {{-- ORIGEM --}}
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

                {{-- DESTINOS / WAYPOINTS --}}
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
                        {{-- linha inicial criada via JS, mas deixo um fallback --}}
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

            {{-- Lista visual dos waypoints na ordem calculada --}}
            <div id="listaWaypoints" class="mt-2 hidden">
                <p class="text-[11px] font-medium text-slate-500 mb-1">
                    Ordem dos pontos da rota:
                </p>
                <ol class="text-xs text-slate-600 space-y-1" id="listaWaypointsItens"></ol>
            </div>
        </section>

        {{-- RF05 – RASTREAR COLETAS EM TEMPO REAL --}}
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

        {{-- RF07 – AGENDAR COLETAS --}}
        <section class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 space-y-3">
            <header>
                <h2 class="text-sm font-semibold text-slate-800">
                    Agendar coleta
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Registre um novo pedido de coleta informando endereços, data e detalhes.
                </p>
            </header>

            {{-- Ajuste a rota abaixo para o seu controller de Pedido (ex.: pedidos.store) --}}
            <form
    method="POST"
    action="{{ route('pedidos.store') }}"
    class="space-y-3"
>

                @csrf

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
@endsection
@push('scripts')
    {{-- Google Maps JS --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places,geometry"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ============================================================
            // CONFIGURAÇÃO INICIAL E DADOS DO BLADE
            // ============================================================
            
          
const dadosIniciais = {
                origem: {!! json_encode($origem ?? null) !!},
                destino: {!! json_encode($destino ?? null) !!},
                posicaoAtual: {!! json_encode($posicaoAtual ?? null) !!},
                rotaUrl: "{{ route('tracking.otimizar') }}",
                csrfToken: "{{ csrf_token() }}"
            };

            let map;
            let rotaPolyline = null;
            let rotaBounds = null;
            let markers = [];
            let trackingMarker = null;
            let trackingInterval = null;

            function initMap() {
                // Define o centro: usa a posição atual do entregador, ou a origem, ou um fallback (SP)
                const center = dadosIniciais.posicaoAtual || dadosIniciais.origem || { lat: -23.55052, lng: -46.633308 };

                map = new google.maps.Map(document.getElementById('map'), {
                    center: center,
                    zoom: 14,
                    mapTypeControl: false,
                    streetViewControl: false,
                });

                // Se já houver dados vindos do controller (Visualização de Pedido Específico)
                if (dadosIniciais.origem && dadosIniciais.destino) {
                    renderizarPontosIniciais();
                }
            }

            function renderizarPontosIniciais() {
                // Marcador Origem (Loja)
                new google.maps.Marker({
                    position: dadosIniciais.origem,
                    map: map,
                    title: "Origem",
                    icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                });

                // Marcador Destino (Cliente)
                new google.maps.Marker({
                    position: dadosIniciais.destino,
                    map: map,
                    title: "Entrega",
                    icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
                });

                // Marcador Entregador (Se existir)
                if (dadosIniciais.posicaoAtual) {
                    trackingMarker = new google.maps.Marker({
                        position: dadosIniciais.posicaoAtual,
                        map: map,
                        title: "Entregador",
                        icon: 'http://maps.google.com/mapfiles/ms/icons/truck.png'
                    });
                }
            }

            initMap();

            // ============================================================
            // RF04 / RF10 – GERAR ROTA OTIMIZADA
            // ============================================================
            const formRotas = document.getElementById('formRotas');
            const destinosContainer = document.getElementById('destinosContainer');
            const btnAddDestino = document.getElementById('btnAddDestino');
            const rotaStatus = document.getElementById('rotaStatus');

            // Adicionar novo input de destino
            if(btnAddDestino) {
                btnAddDestino.addEventListener('click', () => {
                    const row = document.createElement('div');
                    row.className = 'flex gap-2 destino-row';
                    row.innerHTML = `
                        <input type="text" name="destinos[]" placeholder="Ex.: Rua do Porto, 200 - Campinas"
                            class="flex-1 rounded-lg border-slate-200 text-sm shadow-sm focus:border-entrego-blue focus:ring-entrego-blue" required>
                        <button type="button" class="btn-remove-destino text-xs text-slate-400 hover:text-rose-500" title="Remover">✕</button>
                    `;
                    destinosContainer.appendChild(row);
                });
            }

            // Remover destino
            if(destinosContainer) {
                destinosContainer.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-remove-destino')) {
                        e.target.closest('.destino-row').remove();
                    }
                });
            }

            // Submit do Formulário de Rota
            if(formRotas) {
                formRotas.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    rotaStatus.textContent = 'Calculando rota...';
                    rotaStatus.className = 'text-[11px] text-slate-400';

                    const origem = document.getElementById('origemInput').value;
                    const destinos = Array.from(document.querySelectorAll('input[name="destinos[]"]'))
                                        .map(input => input.value)
                                        .filter(val => val.length > 0);

                    try {
                        const response = await fetch(dadosIniciais.rotaUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': dadosIniciais.csrfToken
                            },
                            body: JSON.stringify({ origem, destinos })
                        });

                        const data = await response.json();

                        if (!response.ok || !data.routes || !data.routes.length) {
                            throw new Error(data.message || 'Rota não encontrada.');
                        }

                        desenharRotaNoMapa(data.routes[0]);
                        rotaStatus.textContent = 'Rota gerada com sucesso!';
                        rotaStatus.className = 'text-[11px] text-emerald-600';

                    } catch (err) {
                        console.error(err);
                        rotaStatus.textContent = 'Erro: ' + err.message;
                        rotaStatus.className = 'text-[11px] text-rose-500';
                    }
                });
            }

            function desenharRotaNoMapa(route) {
                // Limpar rota anterior
                if (rotaPolyline) rotaPolyline.setMap(null);

                const points = google.maps.geometry.encoding.decodePath(route.overview_polyline.points);
                
                rotaPolyline = new google.maps.Polyline({
                    path: points,
                    map: map,
                    strokeColor: "#2563EB", // entrego-blue
                    strokeOpacity: 0.8,
                    strokeWeight: 5,
                });

                const bounds = new google.maps.LatLngBounds();
                points.forEach(p => bounds.extend(p));
                map.fitBounds(bounds);

                // Atualizar painel de resumo (se existir os elementos)
                const distElement = document.getElementById('rotaDistancia');
                if(distElement) {
                    let totalMetros = 0;
                    let totalSegundos = 0;
                    route.legs.forEach(leg => {
                        totalMetros += leg.distance.value;
                        totalSegundos += leg.duration.value;
                    });
                    distElement.textContent = (totalMetros / 1000).toFixed(1) + ' km';
                    document.getElementById('rotaTempo').textContent = Math.round(totalSegundos / 60) + ' min';
                }
            }

            // ============================================================
            // RF05 – RASTREAMENTO LIVE
            // ============================================================
            const btnIniciar = document.getElementById('btnIniciarRastreamento');
            const btnParar = document.getElementById('btnPararRastreamento');
            const inputRotaId = document.getElementById('rotaIdTracking');
            const statusTracking = document.getElementById('trackingStatus');

            if(btnIniciar) {
                btnIniciar.addEventListener('click', () => {
                    const rotaId = inputRotaId.value;
                    if(!rotaId) return alert('Informe o ID da rota');

                    statusTracking.textContent = 'Rastreando...';
                    
                    if(trackingInterval) clearInterval(trackingInterval);

                    // Função de polling
                    const fetchPosicao = async () => {
                        try {
                            // Atenção: Ajuste a URL para corresponder à sua rota Laravel
                            const res = await fetch(`/tracking/rotas/${rotaId}/posicao`);
                            const data = await res.json();

                            if(data.latitude && data.longitude) {
                                const novaPos = { 
                                    lat: parseFloat(data.latitude), 
                                    lng: parseFloat(data.longitude) 
                                };

                                if(!trackingMarker) {
                                    trackingMarker = new google.maps.Marker({
                                        position: novaPos,
                                        map: map,
                                        icon: 'http://maps.google.com/mapfiles/ms/icons/truck.png',
                                        title: "Veículo"
                                    });
                                } else {
                                    trackingMarker.setPosition(novaPos);
                                }
                            }
                        } catch(e) {
                            console.log("Erro no rastreamento:", e);
                        }
                    };

                    fetchPosicao(); // Chama imediatamente
                    trackingInterval = setInterval(fetchPosicao, 5000); // Repete a cada 5s
                });
            }

            if(btnParar) {
                btnParar.addEventListener('click', () => {
                    if(trackingInterval) clearInterval(trackingInterval);
                    statusTracking.textContent = 'Rastreamento parado.';
                });
            }
        });
    </script>
@endpush