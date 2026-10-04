<!doctype html>
<html lang="id" data-default-theme="@yield('default-theme', 'dark')">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>@yield('title', 'Ringkasan') | RuangTugas</title>
    <script>
        let savedTheme = null;
        try {
            savedTheme = localStorage.getItem('praktikum.theme');
        } catch (error) {
            console.error('Preferensi tema tidak dapat dibaca.', error);
        }
        if (savedTheme === 'light' || savedTheme === 'dark') {
            document.documentElement.dataset.theme = savedTheme;
        } else if (document.documentElement.dataset.defaultTheme === 'light' || document.documentElement.dataset.defaultTheme === 'dark') {
            document.documentElement.dataset.theme = document.documentElement.dataset.defaultTheme;
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.dataset.theme = 'dark';
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-paper-100 font-sans text-ink-900 antialiased">
    <a href="#konten-utama" class="sr-only z-50 rounded-md bg-white px-4 py-3 text-ink-900 focus:not-sr-only focus:fixed focus:left-4 focus:top-4">
        Lewati ke konten utama
    </a>

    <div class="min-h-screen md:flex">
        <aside class="hidden w-56 shrink-0 flex-col border-r border-white/10 bg-pine-950 px-4 py-6 text-white xl:flex">
            <a href="{{ route('dashboard') }}" class="mb-8 flex items-center gap-3 px-1 text-white focus-visible:outline-offset-4">
                <span class="brand-mark inline-flex size-9 shrink-0 items-center justify-center rounded-md border border-emerald-500/30 bg-emerald-500/10 font-mono text-sm font-bold text-emerald-400" aria-hidden="true">RT</span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold tracking-tight">RuangTugas</span>
                    <span class="mt-0.5 block text-[11px] leading-4 text-slate-300">Projek &amp; Praktikum Hub</span>
                </span>
            </a>
            <p class="mb-2 px-2 text-[11px] font-semibold tracking-wide text-slate-300">RUANG KERJA</p>
            <nav aria-label="Navigasi utama" class="flex flex-col gap-1">
                <a href="{{ route('dashboard') }}" @class(['sidebar-link flex min-h-11 items-center gap-3 rounded-md px-2 text-sm font-medium text-white hover:bg-pine-800', 'bg-pine-800' => request()->routeIs('dashboard')]) @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                        <rect x="13.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                        <rect x="3.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                        <rect x="13.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                    </svg>
                    Ringkasan
                </a>
                <a href="{{ route('projects.index') }}" @class(['sidebar-link flex min-h-11 items-center gap-3 rounded-md px-2 text-sm font-medium text-white hover:bg-pine-800', 'bg-pine-800' => request()->routeIs('projects.*')]) @if(request()->routeIs('projects.*')) aria-current="page" @endif>
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3.75 7.5h6.5l2 2h8v8.75a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z"></path>
                        <path d="M3.75 7.5v-2a2 2 0 0 1 2-2h4.5l2 2h3"></path>
                    </svg>
                    Proyek
                </a>
                <a href="{{ route('tasks.index') }}" @class(['sidebar-link flex min-h-11 items-center gap-3 rounded-md px-2 text-sm font-medium text-white hover:bg-pine-800', 'bg-pine-800' => request()->routeIs('tasks.*')]) @if(request()->routeIs('tasks.*')) aria-current="page" @endif>
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4.25" y="3.75" width="15.5" height="17" rx="2"></rect>
                        <path d="M8 8h8M8 12h8M8 16h5"></path>
                        <path d="m6.5 8 .75.75L8.5 7.5"></path>
                    </svg>
                    Tugas
                </a>
            </nav>
            <button type="button" data-theme-toggle aria-label="Ganti ke tema gelap" class="mt-4 flex min-h-11 w-full items-center rounded-md border border-slate-500 px-2 text-left text-sm font-medium text-white hover:bg-pine-800">
                Tema terang
            </button>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="border-b border-[var(--border)] bg-white px-4 py-3 sm:px-6 xl:hidden">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <a href="{{ route('dashboard') }}" class="flex min-h-11 items-center gap-2.5 text-ink-900">
                        <span class="brand-mark inline-flex size-8 shrink-0 items-center justify-center rounded-md border border-emerald-500/30 bg-emerald-500/10 font-mono text-xs font-bold text-emerald-400" aria-hidden="true">RT</span>
                        <span class="min-w-0">
                            <span class="block text-sm font-semibold tracking-tight">RuangTugas</span>
                            <span class="block text-[10px] leading-4 text-slate-600">Projek &amp; Praktikum Hub</span>
                        </span>
                    </a>
                    <button type="button" data-theme-toggle aria-label="Ganti ke tema gelap" class="button button-secondary px-3 text-sm">
                        Tema terang
                    </button>
                </div>
                <nav aria-label="Navigasi utama" class="mt-3 grid grid-cols-3 gap-1">
                    <a href="{{ route('dashboard') }}" @class(['sidebar-link flex min-h-11 items-center justify-center gap-1.5 rounded-md px-2 text-xs font-medium text-pine-950 hover:bg-paper-100', 'bg-paper-100' => request()->routeIs('dashboard')]) @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                            <rect x="13.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                            <rect x="3.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                            <rect x="13.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                        </svg>
                        Ringkasan
                    </a>
                    <a href="{{ route('projects.index') }}" @class(['sidebar-link flex min-h-11 items-center justify-center gap-1.5 rounded-md px-2 text-xs font-medium text-pine-950 hover:bg-paper-100', 'bg-paper-100' => request()->routeIs('projects.*')]) @if(request()->routeIs('projects.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3.75 7.5h6.5l2 2h8v8.75a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z"></path>
                            <path d="M3.75 7.5v-2a2 2 0 0 1 2-2h4.5l2 2h3"></path>
                        </svg>
                        Proyek
                    </a>
                    <a href="{{ route('tasks.index') }}" @class(['sidebar-link flex min-h-11 items-center justify-center gap-1.5 rounded-md px-2 text-xs font-medium text-pine-950 hover:bg-paper-100', 'bg-paper-100' => request()->routeIs('tasks.*')]) @if(request()->routeIs('tasks.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="4.25" y="3.75" width="15.5" height="17" rx="2"></rect>
                            <path d="M8 8h8M8 12h8M8 16h5"></path>
                            <path d="m6.5 8 .75.75L8.5 7.5"></path>
                        </svg>
                        Tugas
                    </a>
                </nav>
            </header>

            <main id="konten-utama" class="mx-auto w-full max-w-screen-2xl px-4 py-5 sm:px-6 sm:py-6 lg:px-8 lg:py-7 @yield('main-class')">
                <p id="page-loading" hidden role="status" aria-live="polite" class="mb-5 rounded-md border border-[var(--border-strong)] bg-white px-4 py-3 text-sm font-medium text-ink-900">
                    Memuat halaman...
                </p>

                @if (session('success'))
                    <div role="status" class="mb-5 rounded-md border border-[var(--border-strong)] bg-white px-4 py-3 text-sm font-medium text-ink-900">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div role="alert" class="mb-5 rounded-md border border-clay-700 bg-white px-4 py-3 text-sm font-medium text-clay-700">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
