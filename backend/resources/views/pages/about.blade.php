@extends('layouts.public')

@section('title', 'Hakkımızda | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h1 class="h2 fw-semibold">Mimarist Sandalye</h1>
                    <p class="lead text-secondary">Profesyonel mekanlar için sandalye seçimini katalogdan teklif sürecine taşıyan sade ve güvenilir bir platform.</p>
                    <p>Restoran, otel, cafe, ofis ve özel projelerde; ürün, renk, malzeme ve adet ihtiyaçlarını netleştirerek hızlı teklif akışı sunuyoruz.</p>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid rounded-3" loading="lazy" src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1000&q=80" alt="Mimarist Sandalye showroom">
                </div>
            </div>
        </div>
    </main>
@endsection
