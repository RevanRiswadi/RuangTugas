@extends('layouts.app')

@section('title', 'Tugas')

@section('content')
    <div class="flex flex-col gap-4 border-b border-[var(--border)] pb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">Tugas</h1>
            <p class="mt-1 text-xs leading-5 text-slate-600">Atur deadline, prioritas, anggota tim, dan status tugas.</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="button button-primary self-start sm:self-auto">Tambah tugas</a>
    </div>

    <form method="GET" action="{{ route('tasks.index') }}" class="surface-card mt-4 grid gap-3 p-4 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto_auto] sm:items-end sm:p-5">
        <div>
            <label for="filter-mata-pelajaran" class="mb-1.5 block text-sm font-semibold text-ink-900">Mata pelajaran</label>
            <select id="filter-mata-pelajaran" name="mata_pelajaran" class="field-control">
                <option value="">Semua mata pelajaran</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject }}" @selected(($filters['mata_pelajaran'] ?? '') === $subject)>{{ $subject }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filter-prioritas" class="mb-1.5 block text-sm font-semibold text-ink-900">Prioritas</label>
            <select id="filter-prioritas" name="prioritas" class="field-control">
                <option value="">Semua prioritas</option>
                @foreach (['Rendah', 'Sedang', 'Tinggi'] as $priority)
                    <option value="{{ $priority }}" @selected(($filters['prioritas'] ?? '') === $priority)>{{ $priority }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="button button-secondary">Terapkan filter</button>
        @if ($filters)
            <a href="{{ route('tasks.index') }}" class="text-link inline-flex min-h-11 items-center text-sm font-semibold">Hapus filter</a>
        @endif
    </form>

    @if ($tasks->isEmpty())
        <div class="surface-card mt-4 flex flex-col items-center px-5 py-10 text-center sm:px-8">
            <span class="empty-state-icon mb-4 flex h-10 w-10 items-center justify-center rounded-xl" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="4" width="14" height="17" rx="2"></rect>
                    <path d="M9 4.5h6M9 10h6M9 14h6M9 18h3"></path>
                </svg>
            </span>
            @if ($filters)
                <h2 class="text-sm font-bold text-ink-900">Tidak ada tugas yang cocok</h2>
                <p class="mt-1.5 max-w-md text-xs leading-5 text-slate-600">Coba ubah pilihan mata pelajaran atau prioritas.</p>
                <a href="{{ route('tasks.index') }}" class="button button-secondary mt-4">Hapus filter</a>
            @else
                <h2 class="text-sm font-bold text-ink-900">Belum ada tugas</h2>
                <p class="mt-1.5 max-w-md text-xs leading-5 text-slate-600">Tambahkan tugas dan hubungkan ke proyek yang sudah dibuat.</p>
                <a href="{{ route('tasks.create') }}" class="button button-primary mt-4">Tambah tugas</a>
            @endif
        </div>
    @else
        <div class="surface-card mt-5 hidden overflow-x-auto xl:block">
            <table class="w-full min-w-[760px] border-collapse text-left">
                <thead>
                    <tr class="border-b border-[var(--border)] bg-[var(--surface-muted)]">
                        <th scope="col" class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Tugas</th>
                        <th scope="col" class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Proyek / Mata pelajaran</th>
                        <th scope="col" class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Deadline</th>
                        <th scope="col" class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Prioritas</th>
                        <th scope="col" class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Status</th>
                        <th scope="col" class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-600">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @foreach ($tasks as $task)
                        <tr>
                            <th scope="row" class="max-w-56 px-4 py-3 align-middle font-semibold text-ink-900">
                                <span class="block break-words">{{ $task->judul_tugas }}</span>
                                @if ($task->anggota_tim)
                                    <span class="mt-0.5 block break-words text-xs font-normal text-slate-600">Tim: {{ $task->anggota_tim }}</span>
                                @endif
                            </th>
                            <td class="max-w-48 px-4 py-3 align-middle text-sm">
                                <span class="block break-words font-medium text-ink-900">{{ $task->project->nama_proyek }}</span>
                                <span class="mt-0.5 block break-words text-xs text-slate-600">{{ $task->project->mata_pelajaran }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 align-middle font-mono text-xs tabular-nums text-ink-900">
                                <time datetime="{{ $task->deadline->format('Y-m-d') }}">{{ $task->deadline->format('d/m/Y') }}</time>
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <span @class([
                                    'badge',
                                    'badge-priority-high' => $task->prioritas === 'Tinggi',
                                    'badge-priority-medium' => $task->prioritas === 'Sedang',
                                    'badge-priority-low' => $task->prioritas === 'Rendah',
                                ])>{{ $task->prioritas }}</span>
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <span @class([
                                    'badge mb-2',
                                    'badge-status-pending' => $task->status === 'Belum Dimulai',
                                    'badge-status-progress' => $task->status === 'Dalam Proses',
                                    'badge-status-done' => $task->status === 'Selesai',
                                ])>{{ $task->status }}</span>
                                <form action="{{ route('tasks.status', $task) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label for="status-{{ $task->id }}" class="sr-only">Status {{ $task->judul_tugas }}</label>
                                    <select id="status-{{ $task->id }}" name="status" class="field-control min-h-9 w-auto max-w-44 py-1.5 text-sm">
                                        @foreach (['Belum Dimulai', 'Dalam Proses', 'Selesai'] as $status)
                                            <option value="{{ $status }}" @selected($task->status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="button button-secondary min-h-9 px-3 py-1.5 text-sm" data-loading-text="Menyimpan...">Simpan</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('tasks.edit', $task) }}" class="button button-secondary min-h-9 px-3 py-1.5 text-sm">Ubah</a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" data-confirm="Hapus tugas ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button button-secondary min-h-9 px-3 py-1.5 text-sm text-clay-700" data-loading-text="Menghapus...">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 grid gap-4 xl:hidden">
            @foreach ($tasks as $task)
                <article class="surface-card task-list-card min-w-0 p-4 sm:p-5">
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <h2 class="min-w-0 break-words text-sm font-semibold tracking-tight text-ink-900">{{ $task->judul_tugas }}</h2>
                        <span @class([
                            'badge shrink-0',
                            'badge-status-pending' => $task->status === 'Belum Dimulai',
                            'badge-status-progress' => $task->status === 'Dalam Proses',
                            'badge-status-done' => $task->status === 'Selesai',
                        ])>{{ $task->status }}</span>
                    </div>
                    <p class="mt-1 break-words text-xs text-slate-600">{{ $task->project->nama_proyek }} <span aria-hidden="true">·</span> {{ $task->project->mata_pelajaran }}</p>
                    @if ($task->anggota_tim)
                        <p class="mt-2 break-words text-xs text-slate-600">Anggota tim: {{ $task->anggota_tim }}</p>
                    @endif
                    <dl class="mt-3 grid grid-cols-2 gap-3 text-sm">
                        <div><dt class="font-medium text-slate-600">Deadline</dt><dd class="mt-1 font-mono text-xs tabular-nums text-ink-900"><time datetime="{{ $task->deadline->format('Y-m-d') }}">{{ $task->deadline->format('d/m/Y') }}</time></dd></div>
                        <div><dt class="font-medium text-slate-600">Prioritas</dt><dd class="mt-1"><span @class([
                            'badge',
                            'badge-priority-high' => $task->prioritas === 'Tinggi',
                            'badge-priority-medium' => $task->prioritas === 'Sedang',
                            'badge-priority-low' => $task->prioritas === 'Rendah',
                        ])>{{ $task->prioritas }}</span></dd></div>
                    </dl>
                    <form action="{{ route('tasks.status', $task) }}" method="POST" class="mt-4 flex flex-wrap items-end gap-2">
                        @csrf
                        @method('PATCH')
                        <div class="min-w-0 flex-1">
                            <label for="mobile-status-{{ $task->id }}" class="mb-1.5 block text-sm font-semibold text-ink-900">Status</label>
                            <select id="mobile-status-{{ $task->id }}" name="status" class="field-control">
                                @foreach (['Belum Dimulai', 'Dalam Proses', 'Selesai'] as $status)
                                    <option value="{{ $status }}" @selected($task->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="button button-secondary" data-loading-text="Menyimpan...">Simpan</button>
                    </form>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('tasks.edit', $task) }}" class="button button-secondary">Ubah</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" data-confirm="Hapus tugas ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-secondary text-clay-700" data-loading-text="Menghapus...">Hapus</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6">{{ $tasks->links() }}</div>
    @endif
@endsection
