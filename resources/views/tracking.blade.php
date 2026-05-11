@extends('layouts.app')

@section('title', 'Tracking')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

        {{-- Card de informações da rota atual --}}
        @if($rota)
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Rota em Monitoramento</h3>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                        @if($rota->status === 'iniciada') bg-green-100 text-green-700
                        @elseif($rota->status === 'planejada') bg-blue-100 text-blue-700
                        @elseif($rota->status === 'concluida') bg-gray-100 text-gray-600
                        @else bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($rota->status) }}
                    </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-1">Coleta</span>
                        <p class="text-gray-800">
                            {{ $rota->pedido?->enderecoColeta?->logradouro }},
                            {{ $rota->pedido?->enderecoColeta?->numero }}
                            — {{ $rota->pedido?->enderecoColeta?->cidade }}/{{ $rota->pedido?->enderecoColeta?->estado }}
                        </p>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-1">Entrega</span>
                        <p class="text-gray-800">
                            {{ $rota->pedido?->enderecoEntrega?->logradouro }},
                            {{ $rota->pedido?->enderecoEntrega?->numero }}
                            — {{ $rota->pedido?->enderecoEntrega?->cidade }}/{{ $rota->pedido?->enderecoEntrega?->estado }}
                        </p>
                    </div>
                    @if($rota->motorista)
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-1">Motorista</span>
                        <p class="text-gray-800">{{ $rota->motorista->nome }}</p>
                    </div>
                    @endif
                    @if($rota->veiculo)
                    <div>
                        <span class="block text-xs font-medium text-gray-500 mb-1">Veículo</span>
                        <p class="text-gray-800">{{ ucfirst($rota->veiculo->tipo) }} — {{ $rota->veiculo->placa }}</p>
                    </div>
                    @endif
                </div>
                @if($posicaoAtual)
                    <p class="mt-3 text-xs text-gray-400">
                        Última posição registrada: {{ $posicaoAtual->data_hora?->format('d/m/Y H:i') }}
                        ({{ number_format($posicaoAtual->latitude, 5) }}, {{ number_format($posicaoAtual->longitude, 5) }})
                    </p>
                @endif
            </div>
        @else
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm text-amber-800">
                Nenhuma rota ativa encontrada no sistema.
            </div>
        @endif

        {{-- Painel do motorista --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 flex flex-col gap-6">

                <header>
                    <h2 class="text-2xl font-bold text-gray-900">Painel do Motorista - Rastreamento</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Ative o rastreamento para iniciar o envio automático das suas coordenadas
                        e visualizar seu trajeto em tempo real.
                    </p>
                </header>

                <div aria-live="polite" class="bg-gray-50 p-5 rounded-md border border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <span class="block text-sm font-medium text-gray-500">Status do Rastreamento</span>
                            <div class="mt-1 flex items-center gap-2">
                                <span id="status-indicator" class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span id="status-text" class="text-base font-semibold text-gray-900">Parado</span>
                            </div>
                        </div>

                        <div>
                            <span class="block text-sm font-medium text-gray-500">Previsão de Chegada</span>
                            <div class="mt-1">
                                <span id="eta-text" class="text-base font-semibold text-gray-900">Aguardando início...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full h-96 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 relative">
                    <div id="map" class="w-full h-full"></div>

                    <div id="map-overlay" class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-80 z-10">
                        <span class="text-gray-600 font-medium">Aguardando sinal de GPS para exibir o mapa...</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @if($rota)
                        <button id="btn-iniciar" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                            Iniciar Rastreamento
                        </button>
                        <button id="btn-parar" class="hidden inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                            Parar Rastreamento
                        </button>
                    @else
                        <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-500 uppercase tracking-widest cursor-not-allowed">
                            Sem Rota Ativa
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    let map;
    let driverMarker;
    let driverPath;
    let pathCoordinates = [];
    let mapInitialized = false;
    let waypointMarkers = [];

    const rotaId = {{ $rotaId ?? 'null' }};

    @if($posicaoAtual)
    const initialLat = {{ $posicaoAtual->latitude }};
    const initialLng = {{ $posicaoAtual->longitude }};
    @else
    const initialLat = -23.550520;
    const initialLng = -46.633308;
    @endif

    async function carregarWaypoints() {
        if (!rotaId) return;
        try {
            const response = await fetch(`/rotas/${rotaId}/waypoints`, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('Erro ao carregar waypoints');

            const waypoints = await response.json();

            waypointMarkers.forEach(marker => marker.setMap(null));
            waypointMarkers = [];

            waypoints.forEach(wp => {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(wp.latitude), lng: parseFloat(wp.longitude) },
                    map: map,
                    draggable: false,
                    icon: "https://maps.google.com/mapfiles/ms/icons/red-dot.png"
                });

                marker.addListener("rightclick", async () => {
                    try {
                        const response = await fetch(`/waypoints/${wp.id}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            }
                        });

                        if (!response.ok) throw new Error("Erro ao remover waypoint");
                        carregarWaypoints();
                    } catch (error) {
                        console.error(error);
                        alert("Não foi possível remover o waypoint.");
                    }
                });

                waypointMarkers.push(marker);
            });
        } catch (error) {
            console.error('Erro ao carregar waypoints:', error);
        }
    }

    function initMap() {
        const initialPos = { lat: initialLat, lng: initialLng };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 16,
            center: initialPos,
            disableDefaultUI: true,
            zoomControl: true,
        });

        driverMarker = new google.maps.Marker({
            position: initialPos,
            map: map,
            title: "Sua Localização",
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                fillColor: "#2563EB",
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: "#FFFFFF"
            }
        });

        if (rotaId) {
            map.addListener("click", async function(event) {
                const latitude = event.latLng.lat();
                const longitude = event.latLng.lng();

                try {
                    const response = await fetch("/waypoints", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            rota_id: rotaId,
                            latitude: latitude,
                            longitude: longitude,
                            ordem: waypointMarkers.length
                        })
                    });

                    if (!response.ok) throw new Error("Erro ao salvar waypoint");
                    carregarWaypoints();
                } catch (error) {
                    console.error(error);
                    alert("Não foi possível salvar o waypoint.");
                }
            });
        }

        driverPath = new google.maps.Polyline({
            path: pathCoordinates,
            geodesic: true,
            strokeColor: "#2563EB",
            strokeOpacity: 0.8,
            strokeWeight: 4,
            map: map
        });

        document.getElementById('map-overlay').style.display = 'none';
        carregarWaypoints();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!rotaId) return;

        const btnIniciar   = document.getElementById('btn-iniciar');
        const btnParar     = document.getElementById('btn-parar');
        const statusText   = document.getElementById('status-text');
        const statusIndicator = document.getElementById('status-indicator');
        const etaText      = document.getElementById('eta-text');
        const mapOverlay   = document.getElementById('map-overlay');

        if (!btnIniciar) return;

        let watchId = null;

        btnIniciar.addEventListener('click', () => {
            if (!('geolocation' in navigator)) {
                alert('Geolocalização não é suportada pelo seu navegador.');
                return;
            }

            btnIniciar.classList.add('hidden');
            btnParar.classList.remove('hidden');
            statusText.textContent = 'Obtendo sinal GPS...';
            statusIndicator.classList.remove('bg-red-500');
            statusIndicator.classList.add('bg-yellow-500');

            watchId = navigator.geolocation.watchPosition(
                posicaoObtidaComSucesso,
                tratarErroDeSinal,
                { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 }
            );
        });

        btnParar.addEventListener('click', () => {
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }

            btnParar.classList.add('hidden');
            btnIniciar.classList.remove('hidden');
            statusText.textContent = 'Parado';
            statusIndicator.classList.remove('bg-green-500', 'bg-yellow-500');
            statusIndicator.classList.add('bg-red-500');
        });

        function posicaoObtidaComSucesso(position) {
            const lat    = position.coords.latitude;
            const lng    = position.coords.longitude;
            const newPos = { lat, lng };

            statusText.textContent = 'Em Rota (Enviando dados)';
            statusIndicator.classList.remove('bg-red-500', 'bg-yellow-500');
            statusIndicator.classList.add('bg-green-500');

            if (map) {
                if (!mapInitialized) {
                    mapOverlay.style.display = 'none';
                    map.setCenter(newPos);
                    mapInitialized = true;
                } else {
                    map.panTo(newPos);
                }

                driverMarker.setPosition(newPos);
                pathCoordinates.push(newPos);
                driverPath.setPath(pathCoordinates);
            }

            fetch(`/tracking/rotas/${rotaId}/localizacao`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ latitude: lat, longitude: lng })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Erro ao salvar localização');
                return data;
            })
            .then(data => {
                etaText.textContent = data.previsao_entrega
                    ? `${data.previsao_entrega.tempo_estimado} (faltam ${data.previsao_entrega.distancia_restante})`
                    : 'Localização enviada com sucesso';
            })
            .catch(error => {
                console.error('Erro na sincronização:', error);
                etaText.textContent = 'Erro ao enviar localização';
            });
        }

        function tratarErroDeSinal(error) {
            console.warn(`Erro de GPS: ${error.message}`);
            statusText.textContent = 'Sinal GPS fraco ou permissão negada';
            statusIndicator.classList.remove('bg-green-500', 'bg-yellow-500');
            statusIndicator.classList.add('bg-red-500');
        }
    });
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"></script>
@endsection
