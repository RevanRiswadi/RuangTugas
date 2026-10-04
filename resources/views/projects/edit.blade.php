@extends('layouts.app')

@section('title', 'Ubah proyek')

@section('content')
    <div class="max-w-3xl">
        <a href="{{ route('projects.index') }}" class="text-link inline-flex min-h-9 items-center text-sm font-semibold">← Kembali ke proyek</a>
        <h1 class="mt-2 text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">Ubah proyek</h1>
        <p class="mt-1 text-xs leading-5 text-slate-600">Perbarui informasi proyek praktikum.</p>
        @include('projects.form')
    </div>
@endsection
