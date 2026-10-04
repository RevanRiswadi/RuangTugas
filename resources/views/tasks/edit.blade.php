@extends('layouts.app')

@section('title', 'Ubah tugas')

@section('content')
    <div class="max-w-3xl">
        <a href="{{ route('tasks.index') }}" class="text-link inline-flex min-h-9 items-center text-sm font-semibold">← Kembali ke tugas</a>
        <h1 class="mt-2 text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">Ubah tugas</h1>
        <p class="mt-1 text-xs leading-5 text-slate-600">Perbarui informasi dan status tugas.</p>
        @include('tasks.form')
    </div>
@endsection
