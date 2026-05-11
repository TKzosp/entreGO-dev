<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<nav class="bg-white shadow-md fixed w-full top-0 z-50" x-data="{ menuOpen: false, profileOpen: false, pedidosOpen: false, suporteOpen: false }">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <a class="text-2xl font-bold text-entrego-blue" href="{{ route('dashboard') }}">entreGO</a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-1">
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="{{ route('tracking') }}">Tracking</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="{{ route('motoristas.index') }}">Motoristas</a>
                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="{{ route('rotas.index') }}">Rotas</a>

                {{-- Dropdown Pedidos --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                            class="py-2 px-3 text-gray-700 hover:text-primary rounded-md flex items-center gap-1">
                        Pedidos
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                         class="absolute left-0 mt-1 w-44 bg-white rounded-md shadow-lg border border-gray-100 z-20">
                        <a href="{{ route('registration') }}"   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Novo Pedido</a>
                        <a href="{{ route('pedidos.index') }}"  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Meus Pedidos</a>
                    </div>
                </div>

                <a class="py-2 px-3 text-gray-700 hover:text-primary rounded-md" href="{{ route('assinaturas.index') }}">Assinaturas</a>

                {{-- Dropdown Suporte --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                            class="py-2 px-3 text-gray-700 hover:text-primary rounded-md flex items-center gap-1">
                        Suporte
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                         class="absolute left-0 mt-1 w-44 bg-white rounded-md shadow-lg border border-gray-100 z-20">
                        <a href="{{ route('support.faq') }}"     class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">FAQ</a>
                        <a href="{{ route('support.contact') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Contato</a>
                        <a href="{{ route('support.tickets') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Meus Chamados</a>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Botão menu mobile -->
                <div class="md:hidden relative">
                    <button @click="menuOpen = !menuOpen" class="focus:outline-none flex items-center justify-center w-10 h-10 rounded-md bg-gray-200 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Menu Mobile (lista plana) -->
                    <div x-show="menuOpen"
                         x-transition
                         @click.away="menuOpen = false"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-md shadow-lg z-20">
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('dashboard') }}">Dashboard</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('tracking') }}">Tracking</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('motoristas.index') }}">Motoristas</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('rotas.index') }}">Rotas</a>
                        <hr class="my-1 border-gray-100">
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('registration') }}">Novo Pedido</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('pedidos.index') }}">Meus Pedidos</a>
                        <hr class="my-1 border-gray-100">
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('assinaturas.index') }}">Assinaturas</a>
                        <hr class="my-1 border-gray-100">
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('support.faq') }}">FAQ</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('support.contact') }}">Contato</a>
                        <a class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('support.tickets') }}">Meus Chamados</a>
                    </div>
                </div>

                <!-- Menu Perfil -->
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" class="focus:outline-none flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A9.003 9.003 0 0112 15c2.212.212 4.232.806 5.879 2.138M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <div x-show="profileOpen"
                         x-transition
                         @click.away="profileOpen = false"
                         class="absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg z-20">
                        <a href="{{ route('profile') }}"            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                        <a href="{{ route('assinaturas.minha') }}"  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Minha Assinatura</a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
