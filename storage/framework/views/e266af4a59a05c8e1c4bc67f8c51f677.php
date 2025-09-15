<?php $__env->startSection('title', 'Dashboard'); ?>


<?php $__env->startSection('content'); ?>
    <section class="container mx-auto p-6" id="dashboard">
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">Dashboard</h1>
        <div class="mb-8 p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Visão Geral</h2>
            <div class="text-gray-600">
                <p>Aqui você pode ver um resumo do status atual das operações de entrega. As principais métricas incluem o número total de viagens realizadas, a quantidade de produtos transportados e a eficiência geral da frota. Esta seção serve como um ponto de partida para monitorar o desempenho e identificar áreas que necessitam de atenção.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Total de viagens realizadas</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Dia</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Mês</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Trimestre</button>
                    </div>
                </div>
                <p class="text-4xl font-bold text-gray-900">12.543</p>
                <p class="text-gray-500 mt-2">Viagens completas no último mês</p>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Total de produtos transportados</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Dia</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Mês</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Trimestre</button>
                    </div>
                </div>
                <p class="text-4xl font-bold text-gray-900">45.789</p>
                <p class="text-gray-500 mt-2">Itens entregues no total</p>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Top motoristas com mais viagens</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Dia</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Mês</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Trimestre</button>
                    </div>
                </div>
                <ul class="text-gray-600 list-disc list-inside mt-4 space-y-2">
                    <li>João Silva (210 viagens)</li>
                    <li>Maria Oliveira (198 viagens)</li>
                    <li>Pedro Santos (185 viagens)</li>
                </ul>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-700">Prazos mais proximos de vencer</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Dia</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Mês</button>
                        <button class="px-3 py-1 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">Trimestre</button>
                    </div>
                </div>
                <ul class="text-gray-600 mt-4 space-y-2">
                    <li>#00123 - Entrega em 12/09/2025</li>
                    <li>#00124 - Entrega em 13/09/2025</li>
                    <li>#00125 - Entrega em 13/09/2025</li>
                </ul>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\rafae\Documents\entreGO-dev\resources\views/dashboard.blade.php ENDPATH**/ ?>