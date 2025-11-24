<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'entreGO')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#6B7280',
                        accent: '#10B981',
                        background: '#F9FAFB',
                        surface: '#FFFFFF',
                        'entrego-blue': '#007BFF',
                    },
                    fontFamily: {
                        roboto: ['Roboto', 'sans-serif'],
                    },
                    borderRadius: {
                        'xl': '0.75rem',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #F3F4F6;
        }
        .material-icons {
            vertical-align: middle;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    @include('layouts.navigation')

    <main class="flex-grow pt-20">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>