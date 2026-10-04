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

    {{-- Command Palette Overlay --}}
    <div id="cmd-overlay" hidden aria-hidden="true" aria-modal="true" role="dialog" aria-label="Command palette" class="cmd-overlay">
        <div id="cmd-modal" class="cmd-modal" role="search">
            <div class="cmd-search-row">
                <svg viewBox="0 0 24 24" fill="none" class="cmd-search-icon" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input id="cmd-input" type="text" placeholder="Cari proyek atau tugas..." autocomplete="off" spellcheck="false" class="cmd-input" aria-controls="cmd-results" aria-autocomplete="list">
                <kbd class="cmd-esc-badge" aria-label="Tekan Escape untuk menutup">esc</kbd>
            </div>
            <ul id="cmd-results" class="cmd-results" role="listbox" aria-label="Hasil pencarian"></ul>
        </div>
    </div>

    <div class="min-h-screen md:flex">
        <aside class="hidden w-56 shrink-0 flex-col border-r border-white/10 bg-pine-950 px-4 py-6 text-white xl:flex">
            <a href="{{ route('dashboard') }}" class="mb-8 flex items-center gap-3 px-1 text-white focus-visible:outline-offset-4">
                <span class="brand-mark inline-flex size-9 shrink-0 items-center justify-center rounded-md border border-emerald-500/30 bg-emerald-500/10 font-mono text-sm font-bold text-emerald-400" aria-hidden="true">RT</span>
                <span class="min-w-0">
                    <span class="block text-sm font-semibold tracking-tight">RuangTugas</span>
                    <span class="mt-0.5 block text-[11px] leading-4 text-slate-300">Projek &amp; Praktikum Hub</span>
                </span>
            </a>

            {{-- Sidebar command bar trigger --}}
            <button type="button" data-cmd-trigger aria-label="Buka command palette (Ctrl K)" class="sidebar-cmd-btn mb-4 flex w-full items-center gap-2 rounded-md border border-white/10 bg-white/5 px-2.5 py-2 font-mono text-xs text-slate-400 hover:border-white/20 hover:bg-white/8 hover:text-slate-300">
                <svg viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5 shrink-0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <span class="min-w-0 flex-1 truncate text-left">Cari...</span>
                <kbd class="shrink-0 rounded border border-white/15 px-1 py-0.5 text-[10px]">⌃K</kbd>
            </button>

            <p class="mb-2 px-2 text-[11px] font-semibold tracking-wide text-slate-300">RUANG KERJA</p>
            <nav aria-label="Navigasi utama" class="flex flex-col gap-1">
                <a href="{{ route('dashboard') }}"
                   data-cmd-item data-cmd-label="Ringkasan" data-cmd-href="{{ route('dashboard') }}" data-cmd-type="halaman"
                   @class(['sidebar-link flex min-h-11 items-center gap-3 rounded-md px-2 text-sm font-medium text-white hover:bg-pine-800', 'bg-pine-800' => request()->routeIs('dashboard')])
                   @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                        <rect x="13.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                        <rect x="3.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                        <rect x="13.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                    </svg>
                    Ringkasan
                </a>
                <a href="{{ route('projects.index') }}"
                   data-cmd-item data-cmd-label="Proyek" data-cmd-href="{{ route('projects.index') }}" data-cmd-type="halaman"
                   @class(['sidebar-link flex min-h-11 items-center gap-3 rounded-md px-2 text-sm font-medium text-white hover:bg-pine-800', 'bg-pine-800' => request()->routeIs('projects.*')])
                   @if(request()->routeIs('projects.*')) aria-current="page" @endif>
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3.75 7.5h6.5l2 2h8v8.75a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z"></path>
                        <path d="M3.75 7.5v-2a2 2 0 0 1 2-2h4.5l2 2h3"></path>
                    </svg>
                    Proyek
                </a>
                <a href="{{ route('tasks.index') }}"
                   data-cmd-item data-cmd-label="Tugas" data-cmd-href="{{ route('tasks.index') }}" data-cmd-type="halaman"
                   @class(['sidebar-link flex min-h-11 items-center gap-3 rounded-md px-2 text-sm font-medium text-white hover:bg-pine-800', 'bg-pine-800' => request()->routeIs('tasks.*')])
                   @if(request()->routeIs('tasks.*')) aria-current="page" @endif>
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4.25" y="3.75" width="15.5" height="17" rx="2"></rect>
                        <path d="M8 8h8M8 12h8M8 16h5"></path>
                        <path d="m6.5 8 .75.75L8.5 7.5"></path>
                    </svg>
                    Tugas
                </a>
                <a href="{{ route('projects.create') }}"
                   data-cmd-item data-cmd-label="Tambah proyek baru" data-cmd-href="{{ route('projects.create') }}" data-cmd-type="aksi"
                   class="hidden"><!-- cmd-only quick action --></a>
                <a href="{{ route('tasks.create') }}"
                   data-cmd-item data-cmd-label="Tambah tugas baru" data-cmd-href="{{ route('tasks.create') }}" data-cmd-type="aksi"
                   class="hidden"><!-- cmd-only quick action --></a>
            </nav>
            <button type="button" data-theme-toggle aria-label="Ganti tema" class="mt-4 flex min-h-11 w-full items-center gap-2.5 rounded-md border border-white/15 px-2 text-left text-sm font-medium text-white hover:bg-pine-800">
                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0 theme-icon-dark" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9z"/>
                </svg>
                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0 theme-icon-light" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                </svg>
                <span class="theme-label-dark">Tema gelap</span>
                <span class="theme-label-light">Tema terang</span>
            </button>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="mobile-header border-b border-[var(--border)] bg-[var(--surface)] px-4 py-3 sm:px-6 xl:hidden">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('dashboard') }}" class="flex min-h-11 items-center gap-2 text-ink-900">
                        <span class="brand-mark inline-flex size-8 shrink-0 items-center justify-center rounded-md border border-emerald-500/30 bg-emerald-500/10 font-mono text-xs font-bold text-emerald-400" aria-hidden="true">RT</span>
                        <span class="min-w-0">
                            <span class="block text-sm font-semibold tracking-tight">RuangTugas</span>
                            <span class="block text-[10px] leading-none text-slate-600">Projek &amp; Praktikum Hub</span>
                        </span>
                    </a>
                    <div class="flex items-center gap-2">
                        <button type="button" data-cmd-trigger aria-label="Cari (Ctrl K)" class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-[var(--border-strong)] bg-[var(--surface)] text-[var(--text-muted)] transition-colors hover:border-[var(--border-hover)] hover:text-[var(--text-primary)]">
                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/>
                            </svg>
                        </button>
                        <button type="button" data-theme-toggle aria-label="Ganti tema" class="mobile-theme-btn inline-flex h-8 items-center gap-1.5 rounded-md border border-[var(--border-strong)] bg-[var(--surface)] px-2.5 text-xs font-semibold text-[var(--text-primary)] transition-colors hover:border-[var(--border-hover)] hover:bg-[var(--surface-muted)]">
                            <svg viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5 shrink-0 theme-icon-dark" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9z"/>
                            </svg>
                            <svg viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5 shrink-0 theme-icon-light" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="4"/>
                                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                            </svg>
                            <span class="theme-label-dark">Gelap</span>
                            <span class="theme-label-light">Terang</span>
                        </button>
                    </div>
                </div>
                <nav aria-label="Navigasi utama" class="mt-2.5">
                    <div class="mobile-segmented-nav grid grid-cols-3">
                        <a href="{{ route('dashboard') }}" @class(['mobile-nav-item flex min-h-10 items-center justify-center gap-1.5 text-xs font-semibold', 'mobile-nav-item--active' => request()->routeIs('dashboard')]) @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                            <svg viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                                <rect x="13.75" y="3.75" width="6.5" height="6.5" rx="1"></rect>
                                <rect x="3.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                                <rect x="13.75" y="13.75" width="6.5" height="6.5" rx="1"></rect>
                            </svg>
                            Ringkasan
                        </a>
                        <a href="{{ route('projects.index') }}" @class(['mobile-nav-item flex min-h-10 items-center justify-center gap-1.5 text-xs font-semibold', 'mobile-nav-item--active' => request()->routeIs('projects.*')]) @if(request()->routeIs('projects.*')) aria-current="page" @endif>
                            <svg viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3.75 7.5h6.5l2 2h8v8.75a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2z"></path>
                                <path d="M3.75 7.5v-2a2 2 0 0 1 2-2h4.5l2 2h3"></path>
                            </svg>
                            Proyek
                        </a>
                        <a href="{{ route('tasks.index') }}" @class(['mobile-nav-item flex min-h-10 items-center justify-center gap-1.5 text-xs font-semibold', 'mobile-nav-item--active' => request()->routeIs('tasks.*')]) @if(request()->routeIs('tasks.*')) aria-current="page" @endif>
                            <svg viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5 shrink-0" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4.25" y="3.75" width="15.5" height="17" rx="2"></rect>
                                <path d="M8 8h8M8 12h8M8 16h5"></path>
                                <path d="m6.5 8 .75.75L8.5 7.5"></path>
                            </svg>
                            Tugas
                        </a>
                    </div>
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
