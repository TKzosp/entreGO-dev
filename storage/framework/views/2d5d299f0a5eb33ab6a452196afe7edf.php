

<?php $__env->startSection('title', 'Meus Chamados'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Meus Chamados</h1>
                <p class="text-slate-500">Acompanhe os chamados abertos no sistema.</p>
            </div>

            <a href="<?php echo e(route('support.contact')); ?>"
               class="rounded-lg bg-entrego-blue px-4 py-2 text-white font-medium hover:opacity-90 transition">
                Novo chamado
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Assunto</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Categoria</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Prioridade</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $chamados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chamado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">#<?php echo e($chamado->id); ?></td>
                                <td class="px-4 py-3"><?php echo e($chamado->assunto); ?></td>
                                <td class="px-4 py-3">
                                    <?php echo e($chamado->categoria === 'assistencia_tecnica' ? 'Assistência técnica' : 'Comercial'); ?>

                                </td>
                                <td class="px-4 py-3 capitalize"><?php echo e($chamado->prioridade); ?></td>
                                <td class="px-4 py-3 capitalize"><?php echo e(str_replace('_', ' ', $chamado->status)); ?></td>
                                <td class="px-4 py-3"><?php echo e($chamado->created_at?->format('d/m/Y H:i')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                                    Você ainda não abriu nenhum chamado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projeto-EntreGO\entreGO-dev-main\resources\views/support/tickets.blade.php ENDPATH**/ ?>