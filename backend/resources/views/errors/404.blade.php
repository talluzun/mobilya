@extends('layouts.public')

@section('title', 'Sayfa Bulunamadı | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container text-center py-lg-5">
            <h1 class="display-5 fw-semibold">Sayfa bulunamadı</h1>
            <p class="text-secondary">Aradığınız sayfa taşınmış veya yayından kaldırılmış olabilir.</p>
            <a class="btn btn-bordeaux" href="{{ route('public.home') }}">Anasayfaya Dön</a>
        </div>
    </main>
@endsection
