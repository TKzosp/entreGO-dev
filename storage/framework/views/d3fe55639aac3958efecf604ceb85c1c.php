

<?php $__env->startSection('title', 'Planos de Assinatura'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Planos de assinatura</h1>
        <p class="text-slate-500 mt-2">
            Escolha o pacote ideal para gerenciar seus benefícios na plataforma.
        </p>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 rounded-lg bg-emerald-100 text-emerald-800 px-4 py-3">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($assinaturaAtual): ?>
        <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 p-4">
            <p class="text-sm text-blue-900">
                Seu plano atual: <strong><?php echo e($assinaturaAtual->plano->nome); ?></strong>
                — R$ <?php echo e(number_format($assinaturaAtual->plano->valor, 2, ',', '.')); ?>

            </p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php $__currentLoopData = $planos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plano): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900"><?php echo e($plano->nome); ?></h2>
                    <p class="mt-2 text-slate-500"><?php echo e($plano->descricao); ?></p>

                    <div class="mt-4">
                        <span class="text-3xl font-bold text-entrego-blue">
                            R$ <?php echo e(number_format($plano->valor, 2, ',', '.')); ?>

                        </span>
                        <span class="text-slate-500">/mês</span>
                    </div>

                    <?php
    $beneficios = $plano->beneficios;

    if (is_string($beneficios)) {
        $beneficios = json_decode($beneficios, true);
    }
?>

<?php if(!empty($beneficios) && is_array($beneficios)): ?>
    <ul class="mt-6 space-y-2">
        <?php $__currentLoopData = $beneficios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beneficio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="text-sm text-slate-700 flex items-start gap-2">
                <span class="text-emerald-500">✔</span>
                <span><?php echo e($beneficio); ?></span>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
<?php endif; ?>
                </div>

                <div class="mt-6">
                    <form action="<?php echo e(route('assinaturas.assinar')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="plano_id" value="<?php echo e($plano->id); ?>">

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-entrego-blue text-white py-3 font-medium hover:opacity-90 transition"
                        >
                            Escolher plano
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projeto-EntreGO\entreGO-dev-main\resources\views/assinaturas/index.blade.php ENDPATH**/ ?>