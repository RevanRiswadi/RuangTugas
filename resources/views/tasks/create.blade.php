@extends('layouts.app')

@section('title', 'Tambah tugas')

@section('content')
    <div class="max-w-3xl">
        <a href="{{ route('tasks.index') }}" class="text-link inline-flex min-h-9 items-center text-sm font-semibold">← Kembali ke tugas</a>
        <h1 class="mt-2 text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">Tambah tugas</h1>
        <p class="mt-1 text-xs leading-5 text-slate-600">Hubungkan tugas ke proyek, lalu tentukan deadline, prioritas, dan status.</p>

        @if ($projects->isEmpty())
            <div class="surface-card mt-6 flex flex-col items-center px-5 py-9 text-center sm:px-8">
                <span class="empty-state-icon mb-4 flex h-10 w-10 items-center justify-center rounded-xl" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7.5h7l2 2h9v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <path d="M3 7.5v-2a2 2 0 0 1 2-2h4l2 2h4"></path>
                    </svg>
                </span>
                <h2 class="text-sm font-bold text-ink-900">Buat proyek terlebih dahulu</h2>
                <p class="mt-2 max-w-md text-xs leading-5 text-slate-600">Setiap tugas harus terhubung ke proyek. Tambahkan proyek sebelum membuat tugas.</p>
                <a href="{{ route('projects.create') }}" class="button button-primary mt-4">Tambah proyek</a>
            </div>
        @else
            @include('tasks.form')
        @endif
    </div>
@endsection
