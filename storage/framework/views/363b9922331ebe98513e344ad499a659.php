<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" aria-label="Título da página">
            <?php echo e(__('Painel do Motorista - Rastreamento')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex flex-col gap-6">

                    <header>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Controle de Rota</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Ative o rastreamento para iniciar o envio automático das suas coordenadas, manter a previsão de entrega atualizada e visualizar seu trajeto.
                        </p>
                    </header>

                    <div aria-live="polite" class="bg-gray-50 dark:bg-gray-900 p-5 rounded-md border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status do Rastreamento</span>
                                <div class="mt-1 flex items-center gap-2">
                                    <span id="status-indicator" class="w-3 h-3 rounded-full bg-red-500"></span>
                                    <span id="status-text" class="text-base font-semibold text-gray-900 dark:text-gray-100">Parado</span>
                                </div>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Previsão de Chegada</span>
                                <div class="mt-1">
                                    <span id="eta-text" class="text-base font-semibold text-gray-900 dark:text-gray-100">Aguardando início...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600 relative">
                        <div id="map" class="w-full h-full" role="region" aria-label="Mapa interativo mostrando a rota do motorista"></div>
                        
                        <div id="map-overlay" class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 bg-opacity-80 z-10">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">Aguardando sinal de GPS para exibir o mapa...</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['id' => 'btn-iniciar','ariaLabel' => 'Iniciar envio de rastreamento']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'btn-iniciar','aria-label' => 'Iniciar envio de rastreamento']); ?>
                            <?php echo e(__('Iniciar Rastreamento')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>

                        <?php if (isset($component)) { $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.danger-button','data' => ['id' => 'btn-parar','class' => 'hidden','ariaLabel' => 'Parar envio de rastreamento']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('danger-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'btn-parar','class' => 'hidden','aria-label' => 'Parar envio de rastreamento']); ?>
                            <?php echo e(__('Parar Rastreamento')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $attributes = $__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__attributesOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11)): ?>
<?php $component = $__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11; ?>
<?php unset($__componentOriginal656e8c5ea4d9a4fa173298297bfe3f11); ?>
<?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Variáveis Globais do Mapa
        let map;
        let driverMarker;
        let driverPath;
        let pathCoordinates = [];
        let mapInitialized = false;

        // ID da Rota vindo do Controller
        const rotaId = <?php echo e($rotaId ?? 1); ?>; 

        // Função chamada pelo script do Google Maps ao carregar
        function initMap() {
            // Configuração inicial do mapa (Fica oculto sob o overlay até receber o 1º GPS)
            const initialPos = { lat: -23.550520, lng: -46.633308 }; // Posição padrão (Ex: São Paulo)

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: initialPos,
                disableDefaultUI: true, // Interface limpa
                zoomControl: true,
            });

            // Cria o marcador do motorista (Bolinha azul estilo GPS)
            driverMarker = new google.maps.Marker({
                position: initialPos,
                map: map,
                title: "Sua Localização",
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    fillColor: "#3B82F6", // Cor azul do Tailwind
                    fillOpacity: 1,
                    strokeWeight: 2,
                    strokeColor: "#FFFFFF"
                }
            });

            // Cria a linha (rastro) que vai seguir o motorista
            driverPath = new google.maps.Polyline({
                path: pathCoordinates,
                geodesic: true,
                strokeColor: "#3B82F6",
                strokeOpacity: 0.8,
                strokeWeight: 4,
                map: map
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const btnIniciar = document.getElementById('btn-iniciar');
            const btnParar = document.getElementById('btn-parar');
            const statusText = document.getElementById('status-text');
            const statusIndicator = document.getElementById('status-indicator');
            const etaText = document.getElementById('eta-text');
            const mapOverlay = document.getElementById('map-overlay');

            let watchId = null;

            // Ação: Iniciar Rastreamento
            btnIniciar.addEventListener('click', () => {
                if (!('geolocation' in navigator)) {
                    alert('Geolocalização não é suportada pelo seu navegador.');
                    return;
                }

                btnIniciar.classList.add('hidden');
                btnParar.classList.remove('hidden');
                statusText.textContent = 'Obtendo sinal GPS...';
                statusIndicator.classList.replace('bg-red-500', 'bg-yellow-500');

                watchId = navigator.geolocation.watchPosition(
                    posicaoObtidaComSucesso,
                    tratarErroDeSinal,
                    { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 }
                );
            });

            // Ação: Parar Rastreamento
            btnParar.addEventListener('click', () => {
                if (watchId !== null) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                }
                btnParar.classList.add('hidden');
                btnIniciar.classList.remove('hidden');
                statusText.textContent = 'Parado';
                statusIndicator.classList.replace('bg-green-500', 'bg-red-500');
                statusIndicator.classList.replace('bg-yellow-500', 'bg-red-500');
            });

            // Sucesso do GPS
            function posicaoObtidaComSucesso(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const newPos = { lat: lat, lng: lng };

                // Atualiza Interface HTML
                statusText.textContent = 'Em Rota (Enviando dados)';
                statusIndicator.classList.replace('bg-yellow-500', 'bg-green-500');
                statusIndicator.classList.replace('bg-red-500', 'bg-green-500');

                // Atualiza o Mapa Visual
                if (map) {
                    if (!mapInitialized) {
                        mapOverlay.style.display = 'none'; // Remove aviso de "Aguardando GPS"
                        map.setCenter(newPos);
                        mapInitialized = true;
                    } else {
                        map.panTo(newPos); // Move a câmera suavemente
                    }
                    
                    driverMarker.setPosition(newPos); // Move a bolinha
                    pathCoordinates.push(newPos); // Adiciona ao histórico da linha
                    driverPath.setPath(pathCoordinates); // Redesenha a linha
                }

                // Envia para o Backend (salva histórico e calcula ETA)
                fetch(`/tracking/rotas/${rotaId}/localizacao`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.previsao_entrega) {
                        etaText.textContent = `${data.previsao_entrega.tempo_estimado} (faltam ${data.previsao_entrega.distancia_restante})`;
                    }
                })
                .catch(error => console.error('Erro na sincronização:', error));
            }

            // Erro do GPS
            function tratarErroDeSinal(error) {
                console.warn(`Erro de GPS: ${error.message}`);
                statusText.textContent = 'Sinal GPS fraco ou permissão negada';
                statusIndicator.classList.replace('bg-green-500', 'bg-red-500');
                statusIndicator.classList.replace('bg-yellow-500', 'bg-red-500');
            }
        });
    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(env('GOOGLE_MAPS_API_KEY')); ?>&callback=initMap"></script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\rafae\Downloads\cópia entrego\resources\views/tracking.blade.php ENDPATH**/ ?>