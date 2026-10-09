<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8F5EE]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Digital Diary' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .font-serif-diary {
            font-family: 'Cormorant Garamond', 'Playfair Display', Georgia, serif;
        }
    </style>
</head>
<body class="min-h-full font-sans antialiased text-stone-800 bg-[#F8F5EE] selection:bg-[#EADBCE] flex flex-col justify-between">

    <!-- Top Admin Bar Navigation -->
    <header class="w-full bg-[#F4EFE6]/80 backdrop-blur-md border-b border-[#E7DEC8] px-6 py-3 sticky top-0 z-50 flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-stone-700 hover:text-stone-900 transition-colors">
                <svg class="w-6 h-6 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="font-serif-diary font-semibold italic text-xl tracking-wide text-stone-800">Diary</span>
            </a>
        </div>

        <nav class="flex items-center gap-4 text-xs font-medium uppercase tracking-wider text-stone-600">
            <a href="{{ route('admin.pages.index') }}" class="px-3 py-1.5 rounded-md hover:bg-[#EADBCE]/50 transition-colors {{ request()->routeIs('admin.pages.index') ? 'bg-[#EADBCE] text-stone-900 font-semibold' : '' }}">
                📁 Kelola Halaman
            </a>
            <a href="{{ route('admin.pages.create') }}" class="px-3.5 py-1.5 rounded-md bg-stone-800 text-stone-100 hover:bg-stone-900 transition-all shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Halaman
            </a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1.5 text-stone-500 hover:text-rose-700 font-medium transition-colors">
                    🚪 Logout
                </button>
            </form>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow w-full py-6 md:py-10 px-2 sm:px-6">
        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm flex items-center justify-between shadow-xs">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
