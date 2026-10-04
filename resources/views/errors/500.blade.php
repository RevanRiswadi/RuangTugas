@extends('layouts.app')

@section('title', 'Terjadi gangguan')

@section('content')
    <section class="max-w-xl border-t border-clay-700 pt-5" aria-labelledby="error-title">
        <h1 id="error-title" class="text-xl font-semibold tracking-tight text-ink-900 sm:text-2xl">Data belum dapat ditampilkan</h1>
        <p class="mt-2 text-xs leading-5 text-slate-600">Permintaan tidak berhasil diproses. Kembali ke ringkasan dan coba lagi. Jika masalah berlanjut, hubungi pengelola aplikasi.</p>
        <a href="{{ route('dashboard') }}" class="button button-primary mt-4">Kembali ke ringkasan</a>
    </section>
@endsection
