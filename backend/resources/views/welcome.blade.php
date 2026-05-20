@extends('layouts.public')

@section('title', config('app.name').' | Proje Bazlı Sandalye Teklifleri')
@section('meta_description', 'Mimarist Sandalye katalog ve proje bazlı sandalye teklif platformu.')

@section('content')
    <main>
        <section class="py-5 bg-ink text-white">
            <div class="container py-lg-5">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <span class="badge text-bg-light mb-3">Proje bazlı sandalye çözümleri</span>
                        <h1 class="display-5 fw-semibold mb-3">Mekanınıza uygun sandalyeyi seçin, teklifinizi profesyonelce alın.</h1>
                        <p class="lead text-white-50 mb-4">Restoran, otel, ofis ve özel konut projeleri için koleksiyon, renk, kaplama ve adet bazlı teklif akışı.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a class="btn btn-bordeaux btn-lg" href="{{ route('public.products.index') }}">Kataloğu İncele</a>
                            <a class="btn btn-outline-light btn-lg" href="{{ route('pages.custom-order') }}">Özel Sipariş</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img class="img-fluid rounded-3 hero-media w-100" loading="lazy" src="https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80" alt="Premium sandalye showroom">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="fw-semibold mb-1">Öne Çıkan Ürünler</h2>
                        <p class="text-secondary mb-0">Teklif talebi için seçili koleksiyon ürünleri.</p>
                    </div>
                    <a class="btn btn-outline-dark" href="{{ route('public.products.index') }}">Tüm Katalog</a>
                </div>
                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-12 col-sm-6 col-lg-3">@include('public.partials.product-card', ['product' => $product])</div>
                    @empty
                        <div class="col-12"><div class="alert alert-light border">Henüz ürün eklenmedi.</div></div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="py-5 page-band">
            <div class="container">
                <h2 class="fw-semibold mb-4">Kategoriler / Koleksiyonlar</h2>
                <div class="row g-3">
                    @forelse($categories as $category)
                        <div class="col-6 col-lg-2">
                            <a class="d-block bg-light border rounded-3 p-3 text-decoration-none text-dark h-100" href="{{ route('public.products.index', ['kategori' => $category->slug]) }}">
                                <span class="fw-semibold">{{ $category->name }}</span>
                            </a>
                        </div>
                    @empty
                        @foreach(['Restoran', 'Cafe', 'Otel', 'Ofis', 'Dış Mekan', 'Özel Tasarım'] as $label)
                            <div class="col-6 col-lg-2"><div class="bg-light border rounded-3 p-3 h-100 fw-semibold">{{ $label }}</div></div>
                        @endforeach
                    @endforelse
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <h2 class="fw-semibold">Proje bazlı üretim ve özel sipariş</h2>
                        <p class="text-secondary mb-0">Ölçü, kaplama, renk ve adet beklentinizi paylaşın; ekibimiz ihtiyaçlarınıza göre teklif hazırlasın.</p>
                    </div>
                    <div class="col-lg-5 text-lg-end">
                        <a class="btn btn-bordeaux btn-lg" href="{{ route('pages.custom-order') }}">Özel Sipariş Formu</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 page-band">
            <div class="container">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="fw-semibold mb-1">Son Projeler</h2>
                        <p class="text-secondary mb-0">Mekanlara göre uygulama örnekleri.</p>
                    </div>
                    <a class="btn btn-outline-dark" href="{{ route('public.projects.index') }}">Projeler</a>
                </div>
                <div class="row g-4">
                    @forelse($projects as $project)
                        <div class="col-12 col-md-4">
                            <a class="card h-100 border-0 shadow-sm text-decoration-none text-dark" href="{{ route('public.projects.show', $project->slug) }}">
                                <img loading="lazy" class="card-img-top" style="height: 220px; object-fit: cover;" src="{{ $project->cover_image ? Storage::url($project->cover_image) : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $project->title }}">
                                <div class="card-body">
                                    <h5>{{ $project->title }}</h5>
                                    <p class="text-secondary mb-0">{{ collect([$project->city, $project->venue_type])->filter()->join(' · ') }}</p>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12"><div class="alert alert-light border">Proje içerikleri yakında eklenecek.</div></div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row g-4">
                    @foreach(['Proje okuması yapan ekip', 'Renk ve kaplama seçenekleri', 'Teklif odaklı net süreç'] as $item)
                        <div class="col-md-4">
                            <div class="bg-white border rounded-3 p-4 h-100">
                                <h5 class="fw-semibold">{{ $item }}</h5>
                                <p class="text-secondary mb-0">Sade katalog deneyimi ve doğru bilgilerle satın alma öncesi karar sürecini kolaylaştırır.</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-5 bg-ink text-white">
            <div class="container text-center">
                <h2 class="fw-semibold">Koleksiyon ürünü seçin, teklifinizi hemen oluşturun.</h2>
                <p class="text-white-50">Doğrudan satış yerine projenize göre hazırlanmış profesyonel teklif süreci.</p>
                <a class="btn btn-bordeaux btn-lg" href="{{ route('public.products.index') }}">Teklif Talep Et</a>
            </div>
        </section>
    </main>
@endsection
