<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'entreGO')</title>

    {{-- Fonts: preload + swap para não bloquear renderização --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
          onload="this.rel='stylesheet'">
    <link rel="preload" as="style"
          href="https://fonts.googleapis.com/icon?family=Material+Icons"
          onload="this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </noscript>

    {{-- CSS e JS compilados pelo Vite (não bloqueante) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #F3F4F6; }
        .material-icons { vertical-align: middle; }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    @include('layouts.navigation')

    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    {{-- Máscaras de input (telefone, CPF, CNPJ, CEP) --}}
    <script src="{{ asset('js/masks.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
