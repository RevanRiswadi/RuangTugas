@extends('layouts.app')

@section('title', 'Ringkasan')
@section('default-theme', 'light')
@section('main-class', 'dashboard-page')

@section('content')
    {{-- Page header --}}
    <div class="dashboard-enter dashboard-enter--1 flex flex-col gap-4 border-b border-[var(--border)] pb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="flex items-center gap-2 text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">
                Ringkasan RuangTugas
                <span class="dashboard-status-dot" role="img" aria-label="Halaman ringkasan aktif"></span>
            </h1>
            <p class="mt-1 text-xs leading-5 text-slate-600">Pantau proyek aktif dan tenggat tugas berikutnya.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('projects.create') }}" class="button button-secondary">Tambah proyek</a>
            <a href="{{ route('tasks.create') }}" class="button button-primary">Tambah tugas</a>
        </div>
    </div>

    {{-- Summary strip — 4 kolom full-width --}}
    <dl class="summary-strip dashboard-card dashboard-enter dashboard-enter--2 mt-4 grid grid-cols-2 divide-x divide-y divide-[var(--border)] sm:grid-cols-4 sm:divide-y-0">
        <div class="summary-stat summary-stat--accent">
            <dt class="text-xs font-medium text-slate-600">Proyek aktif</dt>
            <dd class="font-mono text-xl font-bold tabular-nums tracking-tight text-ink-900">{{ $activeProjectCount }}</dd>
        </div>
        <div class="summary-stat">
            <dt class="text-xs font-medium text-slate-600">Tugas belum selesai</dt>
            <dd class="font-mono text-xl font-bold tabular-nums tracking-tight text-ink-900">{{ $openTaskCount }}</dd>
        </div>
        <div class="summary-stat">
            <dt class="text-xs font-medium text-slate-600">Tugas dalam daftar</dt>
            <dd class="font-mono text-xl font-bold tabular-nums tracking-tight text-ink-900">{{ $upcomingTasks->count() }}</dd>
        </div>
        <div class="summary-stat">
            <dt class="text-xs font-medium text-slate-600">Proyek total</dt>
            <dd class="font-mono text-xl font-bold tabular-nums tracking-tight text-ink-900">{{ $projectCount }}</dd>
        </div>
    </dl>

    {{-- Activity streak — 7 hari terakhir (data riil dari deadline tugas) --}}
    <div class="streak-widget dashboard-enter dashboard-enter--2 mt-3">
        <div class="streak-header">
            <span class="streak-title">Aktivitas 7 Hari</span>
            <span class="streak-sub">Tugas dengan deadline minggu ini</span>
        </div>
        <div class="streak-grid" aria-label="Aktivitas tugas 7 hari terakhir">
            @foreach ($weeklyActivity as $date => $count)
                @php
                    $level = match(true) {
                        $count === 0 => 0,
                        $count === 1 => 1,
                        $count <= 3  => 2,
                        default      => 3,
                    };
                    $dayLabel = \Carbon\Carbon::parse($date)->locale('id')->isoFormat('ddd D/M');
                @endphp
                <div class="streak-cell streak-cell--{{ $level }}"
                     title="{{ $dayLabel }}: {{ $count }} tugas"
                     aria-label="{{ $dayLabel }}, {{ $count }} tugas">
                </div>
            @endforeach
        </div>
        <div class="streak-legend">
            <span>Sedikit</span>
            <span class="streak-cell-sample streak-cell--0"></span>
            <span class="streak-cell-sample streak-cell--1"></span>
            <span class="streak-cell-sample streak-cell--2"></span>
            <span class="streak-cell-sample streak-cell--3"></span>
            <span>Banyak</span>
        </div>
    </div>

    {{-- Main grid: dua kolom tugas dan satu kolom proyek pada desktop. --}}
    <div class="mt-4 grid grid-cols-1 items-start gap-4 lg:grid-cols-3">

        {{-- Tugas mendatang — 2/3 lebar --}}
        <section aria-labelledby="tugas-mendatang" class="surface-card dashboard-card dashboard-enter dashboard-enter--3 min-w-0 lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[var(--border)] px-4 py-3 sm:px-5">
                <div>
                    <h2 id="tugas-mendatang" class="text-sm font-semibold tracking-tight text-ink-900">Tugas mendatang</h2>
                    <p class="mt-0.5 text-xs leading-4 text-slate-600">Belum selesai, diurutkan dari deadline terdekat.</p>
                </div>
                <a href="{{ route('tasks.index') }}" class="text-link inline-flex min-h-11 items-center text-xs font-semibold">Lihat semua tugas</a>
            </div>

            @if ($upcomingTasks->isEmpty())
                <div class="flex flex-col items-center px-4 py-10 text-center">
                    <span class="empty-state-icon mb-3 flex h-10 w-10 items-center justify-center rounded-md" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="4" width="14" height="17" rx="2"></rect>
                            <path d="M9 4.5h6M9 10h6M9 14h6M9 18h3"></path>
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-ink-900">Belum ada tugas mendatang</h3>
                    <p class="mt-1 max-w-xs text-xs leading-5 text-slate-600">Tambahkan tugas dengan deadline hari ini atau setelahnya.</p>
                    <a href="{{ route('tasks.create') }}" class="button button-primary mt-4">Tambah tugas</a>
                </div>
            @else
                {{-- Mobile card list (< md) --}}
                <ul class="task-mobile-list divide-y divide-[var(--border)] md:hidden">
                    @foreach ($upcomingTasks as $task)
                        <li class="task-mobile-card px-4 py-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ route('tasks.edit', $task) }}" class="task-title-link block truncate text-sm font-semibold text-ink-900">{{ $task->judul_tugas }}</a>
                                    <p class="mt-0.5 truncate text-xs text-slate-600">{{ $task->project->nama_proyek }}</p>
                                </div>
                                <span @class([
                                    'badge shrink-0',
                                    'badge-priority-high'   => $task->prioritas === 'Tinggi',
                                    'badge-priority-medium' => $task->prioritas === 'Sedang',
                                    'badge-priority-low'    => $task->prioritas === 'Rendah',
                                ])>{{ $task->prioritas }}</span>
                            </div>
                            <div class="mt-2 flex items-center justify-between gap-2">
                                <time datetime="{{ $task->deadline->format('Y-m-d') }}" class="font-mono text-xs tabular-nums text-slate-600">{{ $task->deadline->format('d/m/Y') }}</time>
                                <span @class([
                                    'badge',
                                    'badge-status-pending'  => $task->status === 'Belum Dimulai',
                                    'badge-status-progress' => $task->status === 'Dalam Proses',
                                    'badge-status-done'     => $task->status === 'Selesai',
                                ])>
                                    @if ($task->status === 'Dalam Proses')
                                        <span class="live-dot" aria-hidden="true"></span>
                                    @endif
                                    {{ $task->status }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- Desktop table (>= md) --}}
                <div class="dashboard-table-scroll hidden overflow-x-auto md:block" tabindex="0" aria-label="Tabel tugas mendatang">
                    <table class="task-table w-full min-w-[520px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-[var(--border)] bg-[var(--surface-muted)]">
                                <th scope="col" class="w-[90px] px-4 py-2 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-600 sm:px-5">Deadline</th>
                                <th scope="col" class="px-2 py-2 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-600">Tugas</th>
                                <th scope="col" class="w-[88px] px-2 py-2 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-600">Prioritas</th>
                                <th scope="col" class="w-[110px] px-2 py-2 pr-4 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-600 sm:pr-5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @foreach ($upcomingTasks as $task)
                                <tr class="task-table-row">
                                    <td class="px-4 py-2.5 align-middle sm:px-5">
                                        <time datetime="{{ $task->deadline->format('Y-m-d') }}" class="font-mono text-xs tabular-nums text-slate-600">
                                            {{ $task->deadline->format('d/m/Y') }}
                                        </time>
                                    </td>
                                    <th scope="row" class="min-w-0 px-2 py-2.5 text-left align-middle font-normal">
                                        <a href="{{ route('tasks.edit', $task) }}" class="task-title-link block truncate font-medium text-ink-900">{{ $task->judul_tugas }}</a>
                                        <p class="mt-0.5 truncate text-xs text-slate-600">{{ $task->project->nama_proyek }}</p>
                                    </th>
                                    <td class="px-2 py-2.5 align-middle">
                                        <span @class([
                                            'badge',
                                            'badge-priority-high'   => $task->prioritas === 'Tinggi',
                                            'badge-priority-medium' => $task->prioritas === 'Sedang',
                                            'badge-priority-low'    => $task->prioritas === 'Rendah',
                                        ])>{{ $task->prioritas }}</span>
                                    </td>
                                    <td class="px-2 py-2.5 pr-4 align-middle sm:pr-5">
                                        <span @class([
                                            'badge',
                                            'badge-status-pending'  => $task->status === 'Belum Dimulai',
                                            'badge-status-progress' => $task->status === 'Dalam Proses',
                                            'badge-status-done'     => $task->status === 'Selesai',
                                        ])>
                                            @if ($task->status === 'Dalam Proses')
                                                <span class="live-dot" aria-hidden="true"></span>
                                            @endif
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Proyek aktif — 1/3 lebar --}}
        <section aria-labelledby="proyek-aktif" class="surface-card dashboard-card dashboard-enter dashboard-enter--4 min-w-0">
            <div class="flex items-center justify-between gap-3 border-b border-[var(--border)] px-4 py-3 sm:px-5">
                <div>
                    <h2 id="proyek-aktif" class="text-sm font-semibold tracking-tight text-ink-900">Proyek aktif</h2>
                    <p class="mt-0.5 text-xs leading-4 text-slate-600">Daftar proyek yang masih berjalan.</p>
                </div>
                <a href="{{ route('projects.index') }}" class="text-link inline-flex min-h-11 items-center text-xs font-semibold">Semua proyek</a>
            </div>

            @if ($activeProjects->isEmpty())
                <div class="flex flex-col items-center px-4 py-10 text-center">
                    <span class="empty-state-icon mb-3 flex h-10 w-10 items-center justify-center rounded-md" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 7.5h7l2 2h9v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <path d="M3 7.5v-2a2 2 0 0 1 2-2h4l2 2h4"></path>
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-ink-900">Belum ada proyek aktif</h3>
                    <p class="mt-1 text-xs leading-5 text-slate-600">Buat proyek untuk mulai mencatat tugas praktikum.</p>
                    <a href="{{ route('projects.create') }}" class="button button-primary mt-4">Tambah proyek</a>
                </div>
            @else
                <ul class="divide-y divide-[var(--border)]">
                    @foreach ($activeProjects as $project)
                        <li class="project-list-row project-list-row--dashboard px-4 py-3 sm:px-5">
                            <a href="{{ route('projects.edit', $project) }}" class="task-title-link block truncate text-sm font-semibold text-ink-900">{{ $project->nama_proyek }}</a>
                            <p class="mt-0.5 truncate text-xs text-slate-600">{{ $project->mata_pelajaran }}</p>
                            <div class="mt-1.5 flex items-center gap-3 font-mono text-xs text-slate-600">
                                <time datetime="{{ $project->deadline->format('Y-m-d') }}">{{ $project->deadline->format('d/m/Y') }}</time>
                                <span class="inline-flex items-center gap-1">
                                    <svg viewBox="0 0 24 24" fill="none" class="h-3 w-3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="4.25" y="3.75" width="15.5" height="17" rx="2"></rect>
                                        <path d="M8 8h8M8 12h8M8 16h5"></path>
                                    </svg>
                                    {{ $project->tasks_count }}
                                </span>
                            </div>
                            @php
                                $completionPercentage = $project->tasks_count > 0
                                    ? (int) round(($project->completed_tasks_count / $project->tasks_count) * 100)
                                    : 0;
                            @endphp
                            <div class="mt-2.5">
                                <div class="mb-1 flex items-center justify-between gap-2 font-mono text-[11px] tabular-nums text-slate-600">
                                    <span>
                                        @if ($project->tasks_count > 0)
                                            {{ $project->completed_tasks_count }}/{{ $project->tasks_count }} tugas selesai
                                        @else
                                            Belum ada tugas
                                        @endif
                                    </span>
                                    <span>{{ $completionPercentage }}%</span>
                                </div>
                                <div
                                    class="project-progress-track"
                                    role="progressbar"
                                    aria-label="Penyelesaian {{ $project->nama_proyek }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                    aria-valuenow="{{ $completionPercentage }}"
                                >
                                    <span class="project-progress-fill" style="--progress: {{ $completionPercentage }}%"></span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

    </div>
@endsection
