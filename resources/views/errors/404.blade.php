@extends('layouts.app')

@section('title', 'Halaman tidak ditemukan')

@section('content')
    <section class="max-w-xl border-t border-[var(--border-strong)] pt-5" aria-labelledby="error-title">
        <h1 id="error-title" class="text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">Halaman tidak ditemukan</h1>
        <p class="mt-2 text-xs leading-5 text-slate-600">Alamat mungkin sudah berubah atau data yang dicari tidak tersedia.</p>
        <a href="{{ route('dashboard') }}" class="button button-primary mt-4">Kembali ke ringkasan</a>
    </section>
@endsection
