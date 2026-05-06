

<?php $__env->startSection('title', 'Minha Assinatura'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Minha assinatura</h1>
        <p class="text-slate-500 mt-2">
            Visualize e gerencie o plano vinculado à sua conta.
        </p>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 rounded-lg bg-emerald-100 text-emerald-800 px-4 py-3">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($assinatura): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xl font-semibold text-slate-900">
                <?php echo e($assinatura->plano->nome); ?>

            </h2>

            <p class="mt-2 text-slate-600">
                <?php echo e($assinatura->plano->descricao); ?>

            </p>

            <div class="mt-4 space-y-2 text-sm text-slate-700">
                <p><strong>Valor:</strong> R$ <?php echo e(number_format($assinatura->plano->valor, 2, ',', '.')); ?>/mês</p>
                <p><strong>Status:</strong> <?php echo e(ucfirst($assinatura->status)); ?></p>
                <p><strong>Início:</strong> <?php echo e(\Carbon\Carbon::parse($assinatura->data_inicio)->format('d/m/Y H:i')); ?></p>
                <p>
                    <strong>Renovação automática:</strong>
                    <?php echo e($assinatura->renovacao_automatica ? 'Ativada' : 'Desativada'); ?>

                </p>
            </div>

            <?php
    $beneficios = $assinatura->plano->beneficios;

    if (is_string($beneficios)) {
        $beneficios = json_decode($beneficios, true);
    }
?>

<?php if(!empty($beneficios) && is_array($beneficios)): ?>
    <div class="mt-6">
        <h3 class="font-semibold text-slate-900 mb-3">Benefícios do plano</h3>
        <ul class="space-y-2">
            <?php $__currentLoopData = $beneficios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="text-sm text-slate-700 flex items-start gap-2">
                    <span class="text-emerald-500">✔</span>
                    <span><?php echo e($beneficio); ?></span>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

            <?php if($assinatura->status === 'ativa'): ?>
                <div class="mt-6 flex gap-3">
                    <a
                        href="<?php echo e(route('assinaturas.index')); ?>"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50"
                    >
                        Trocar plano
                    </a>

                    <form action="<?php echo e(route('assinaturas.cancelar')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button
                            type="submit"
                            class="rounded-lg bg-red-600 text-white px-4 py-2 hover:bg-red-700"
                        >
                            Cancelar assinatura
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <p class="text-slate-600">Você ainda não possui uma assinatura ativa.</p>

            <a
                href="<?php echo e(route('assinaturas.index')); ?>"
                class="inline-block mt-4 rounded-lg bg-entrego-blue text-white px-4 py-2 hover:opacity-90"
            >
                Ver planos
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projeto-EntreGO\entreGO-dev-main\resources\views/assinaturas/minha.blade.php ENDPATH**/ ?>