<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<nav class="bg-white shadow-md fixed w-full top-0 z-50">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <a class="text-2xl font-bold text-entrego-blue" href="<?php echo e(route('dashboard')); ?>">entreGO</a>

            <div class="hidden md:flex items-center space-x-1">
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('tracking')); ?>">Tracking</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="<?php echo e(route('registration')); ?>">Driver & Vehicle Registration</a>
            </div>

<div class="flex items-center space-x-4">
<!-- Certifique-se de ter Alpine.js carregado -->


<div class="flex items-center space-x-4" x-data="{ open: false }">
    <!-- Menu do Usuário -->
    <div class="relative">
        <button @click="open = !open" class="focus:outline-none flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-700">
            <!-- Ícone de perfil genérico -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A9.003 9.003 0 0112 15c2.212 0 4.232.806 5.879 2.138M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </button>

        <!-- Dropdown -->
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-36 bg-white rounded-md shadow-lg z-10 group-hover:block">
            <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

        <div class="hidden mobile-menu md:hidden">
            <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
            <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200" href="<?php echo e(route('tracking')); ?>">Tracking</a>
            <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200" href="<?php echo e(route('registration')); ?>">Driver & Vehicle Registration</a>
        </div>
    </div>
</nav>


<?php /**PATH C:\Users\rafae\Documents\entreGO-dev\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>