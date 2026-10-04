@extends('layouts.app')

@section('title', 'Proyek')

@section('content')
    <div class="flex flex-col gap-4 border-b border-[var(--border)] pb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">Proyek</h1>
            <p class="mt-1 text-xs leading-5 text-slate-600">Kelola proyek praktikum, mata pelajaran, dan deadline.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="button button-primary self-start sm:self-auto">Tambah proyek</a>
    </div>

    @if ($projects->isEmpty())
        <div class="surface-card mt-4 flex flex-col items-center px-5 py-10 text-center sm:px-8">
            <span class="empty-state-icon mb-4 flex h-10 w-10 items-center justify-center rounded-xl" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 7.5h7l2 2h9v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <path d="M3 7.5v-2a2 2 0 0 1 2-2h4l2 2h4"></path>
                </svg>
            </span>
            <h2 class="text-sm font-bold text-ink-900">Belum ada proyek</h2>
            <p class="mt-1.5 max-w-xl text-xs leading-5 text-slate-600">Tambahkan proyek pertama agar tugas dapat dihubungkan ke mata pelajaran dan deadline yang sesuai.</p>
            <a href="{{ route('projects.create') }}" class="button button-primary mt-4">Buat proyek</a>
        </div>
    @else
        <div class="mt-5 grid gap-3">
            @foreach ($projects as $project)
                <article class="surface-card project-list-card grid gap-4 p-4 sm:p-5 md:grid-cols-[minmax(0,1fr)_9rem_8rem_auto] md:items-center md:gap-0">
                    <div class="min-w-0 md:pr-5">
                        <h2 class="break-words text-base font-semibold tracking-tight text-ink-900">{{ $project->nama_proyek }}</h2>
                        <p class="mt-0.5 break-words text-xs text-slate-600">{{ $project->mata_pelajaran }}</p>
                        @if ($project->deskripsi)
                            <p class="mt-1.5 max-w-2xl whitespace-pre-line break-words text-xs leading-5 text-slate-600">{{ $project->deskripsi }}</p>
                        @endif
                    </div>
                    <div class="border-[var(--border)] md:border-l md:px-5">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Deadline</p>
                        <time datetime="{{ $project->deadline->format('Y-m-d') }}" class="mt-1 block font-mono text-sm tabular-nums text-ink-900">{{ $project->deadline->format('d/m/Y') }}</time>
                    </div>
                    <div class="border-[var(--border)] md:border-l md:px-5">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-600">Status</p>
                        <span @class([
                            'badge mt-1',
                            'badge-status-progress' => $project->status === 'Aktif',
                            'badge-status-done' => $project->status === 'Selesai',
                        ])>{{ $project->status }}</span>
                        <p class="mt-1 text-xs text-slate-600">{{ $project->tasks_count }} tugas</p>
                    </div>
                    <div class="flex flex-wrap gap-2 md:border-l md:border-[var(--border)] md:pl-5">
                        <a href="{{ route('projects.edit', $project) }}" class="button button-secondary min-h-9 px-3 py-1.5 text-sm">Ubah</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" data-confirm="Hapus proyek ini? Semua tugas yang terkait juga akan dihapus.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-secondary min-h-9 px-3 py-1.5 text-sm text-clay-700" data-loading-text="Menghapus...">Hapus</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-6">{{ $projects->links() }}</div>
    @endif
@endsection
