<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<nav class="bg-white shadow-md fixed w-full top-0 z-50" x-data="{ menuOpen: false, profileOpen: false }">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <a class="text-2xl font-bold text-entrego-blue" href="<?php echo e(route('dashboard')); ?>">entreGO</a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-1">
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('tracking')); ?>">Tracking</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('registration')); ?>">Cadastro</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('support.faq')); ?>">FAQ</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('support.contact')); ?>">Contato</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('support.tickets')); ?>">Meus Chamados</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('assinaturas.index')); ?>">Assinaturas</a>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Botão menu mobile -->
                <div class="md:hidden relative">
                    <button @click="menuOpen = !menuOpen" class="focus:outline-none flex items-center justify-center w-10 h-10 rounded-md bg-gray-200 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Menu Mobile -->
                    <div x-show="menuOpen"
                         x-transition
                         @click.away="menuOpen = false"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-md shadow-lg z-20">
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="<?php echo e(route('tracking')); ?>">Tracking</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="<?php echo e(route('registration')); ?>">Cadastro</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="<?php echo e(route('support.faq')); ?>">FAQ</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="<?php echo e(route('support.contact')); ?>">Contato</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="<?php echo e(route('support.tickets')); ?>">Meus Chamados</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="<?php echo e(route('assinaturas.index')); ?>">Assinaturas</a>
                    </div>
                </div>

                <!-- Menu Perfil -->
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" class="focus:outline-none flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A9.003 9.003 0 0112 15c2.212 0 4.232.806 5.879 2.138M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <div x-show="profileOpen"
                         x-transition
                         @click.away="profileOpen = false"
                         class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-20">
                        <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav><?php /**PATH C:\projeto-EntreGO\entreGO-dev-main\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>