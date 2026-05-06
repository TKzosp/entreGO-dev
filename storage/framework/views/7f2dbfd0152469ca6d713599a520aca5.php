

<?php $__env->startSection('title', 'FAQ'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Perguntas Frequentes</h1>
        <p class="text-slate-500 mb-8">Encontre respostas rápidas sobre o uso do sistema entreGO.</p>

        <div class="space-y-4">
            <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                    <h2 class="text-lg font-semibold text-slate-800"><?php echo e($faq['pergunta']); ?></h2>
                    <p class="text-slate-600 mt-2"><?php echo e($faq['resposta']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projeto-EntreGO\entreGO-dev-main\resources\views/support/faq.blade.php ENDPATH**/ ?>