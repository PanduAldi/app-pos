<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        {{ $title ?? 'Cafe BarisKode' }}
    </title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('layout.tailwind-config')

    @yield('css')

    <style>
        /* Custom scrollbar for better UI in the lists */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #475569;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display h-screen flex overflow-hidden text-slate-900 dark:text-white">
    <!-- Sidebar Navigation -->
    @include('layout.sidebar')
    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full min-w-0 overflow-hidden">
        <!-- Header -->
        @include('layout.header')
        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @yield('content')
        </div>
    </main>


    @yield('js')

    <script>
        // Simple script to set datemmmm
        const dateElement = document.getElementById('current-date');
        const now = new Date();
        const options = {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        };
        dateElement.textContent = now.toLocaleDateString('en-US', options).replace(',', ' •');
    </script>
</body>

</html>