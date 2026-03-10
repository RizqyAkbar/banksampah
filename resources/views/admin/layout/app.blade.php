<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Juragan Urug</title>

    <link rel="icon" href="{{ asset('images/logoclient.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        .font-heading { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
    @yield('extra_css')
</head>
<body class="flex h-screen overflow-hidden text-slate-800 antialiased selection:bg-green-100">

    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white border-r border-slate-100 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-transform duration-300 ease-in-out -translate-x-full lg:static lg:translate-x-0">
        @include('admin.partials.sidebar')
    </aside>

    <main class="flex flex-1 flex-col overflow-hidden relative">
        
        {{-- LOGIKA NAVBAR: Jika ada section 'header_custom', tampilkan itu. Jika tidak, tampilkan header default --}}
        @if(View::hasSection('header_custom'))
            @yield('header_custom')
        @else
            <header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/80 px-6 backdrop-blur-md z-30">
                <div class="flex items-center gap-4">
                    <button id="openSidebar" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">@yield('header_title')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-600 border border-slate-100">
                        <i class="fa-regular fa-calendar text-slate-400"></i>
                        {{ date('d M Y') }}
                    </div>
                </div>
            </header>
        @endif

        <div class="flex flex-col flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
            
            <footer class="mt-auto pt-10 text-center text-sm font-medium text-slate-400 pb-4">
                &copy; {{ date('Y') }} Juragan Urug. Dashboard Admin.
            </footer>
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        }

        // Gunakan event delegation agar tombol di header custom juga terbaca
        document.addEventListener('click', function(e) {
            if (e.target.closest('#openSidebar')) toggleSidebar();
            if (e.target.closest('#closeSidebar')) toggleSidebar();
            if (e.target.closest('#sidebarOverlay')) toggleSidebar();
        });
    </script>
    @yield('extra_js')
</body>
</html>