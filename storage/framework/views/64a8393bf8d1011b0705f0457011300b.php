

<?php $__env->startSection('title', 'Contato'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Abrir Chamado</h1>
        <p class="text-slate-500 mb-6">Abra um chamado de assistência técnica ou comercial diretamente pelo sistema.</p>

        <?php if($errors->any()): ?>
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('support.contact.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nome</label>
                    <input type="text" name="nome" value="<?php echo e(old('nome', $usuario->nome ?? '')); ?>"
                        class="w-full rounded-lg border-slate-300 focus:border-entrego-blue focus:ring-entrego-blue">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                    <input type="email" name="email" value="<?php echo e(old('email', $usuario->email ?? '')); ?>"
                        class="w-full rounded-lg border-slate-300 focus:border-entrego-blue focus:ring-entrego-blue">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Telefone</label>
                    <input type="text" name="telefone" value="<?php echo e(old('telefone', $usuario->telefone ?? '')); ?>"
                        class="w-full rounded-lg border-slate-300 focus:border-entrego-blue focus:ring-entrego-blue">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Assunto</label>
                    <input type="text" name="assunto" value="<?php echo e(old('assunto')); ?>"
                        class="w-full rounded-lg border-slate-300 focus:border-entrego-blue focus:ring-entrego-blue">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Categoria</label>
                    <select name="categoria" class="w-full rounded-lg border-slate-300 focus:border-entrego-blue focus:ring-entrego-blue">
                        <option value="">Selecione</option>
                        <option value="assistencia_tecnica" <?php if(old('categoria') === 'assistencia_tecnica'): echo 'selected'; endif; ?>>Assistência técnica</option>
                        <option value="comercial" <?php if(old('categoria') === 'comercial'): echo 'selected'; endif; ?>>Comercial</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Prioridade</label>
                    <select name="prioridade" class="w-full rounded-lg border-slate-300 focus:border-entrego-blue focus:ring-entrego-blue">
                        <option value="baixa" <?php if(old('prioridade') === 'baixa'): echo 'selected'; endif; ?>>Baixa</option>
                        <option value="media" <?php if(old('prioridade', 'media') === 'media'): echo 'selected'; endif; ?>>Média</option>
                        <option value="alta" <?php if(old('prioridade') === 'alta'): echo 'selected'; endif; ?>>Alta</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mensagem</label>
                <textarea name="mensagem" rows="6"
                    class="w-full rounded-lg border-slate-300 focus:border-entrego-blue focus:ring-entrego-blue"
                    placeholder="Descreva seu problema ou dúvida..."><?php echo e(old('mensagem')); ?></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="rounded-lg bg-entrego-blue px-5 py-3 text-white font-medium hover:opacity-90 transition">
                    Enviar chamado
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\projeto-EntreGO\entreGO-dev-main\resources\views/support/contact.blade.php ENDPATH**/ ?>