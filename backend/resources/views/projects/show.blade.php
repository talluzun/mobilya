@extends('layouts.public')

@section('title', $project->title.' | '.config('app.name'))
@section('meta_description', Str::limit(strip_tags($project->description ?: $project->title), 155))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="mb-4">
                <span class="badge text-bg-light border mb-2">{{ collect([$project->city, $project->venue_type])->filter()->join(' · ') }}</span>
                <h1 class="h2 fw-semibold">{{ $project->title }}</h1>
            </div>
            <img loading="lazy" class="img-fluid rounded-3 w-100 mb-4" style="max-height:520px;object-fit:cover;" src="{{ $project->cover_image ? Storage::url($project->cover_image) : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1400&q=80' }}" alt="{{ $project->title }}">
            <div class="bg-white border rounded-3 p-4 mb-5">{!! $project->description ?: 'Proje açıklaması yakında.' !!}</div>

            @if($project->media->count())
                <h2 class="h4 fw-semibold mb-3">Galeri</h2>
                <div class="row g-3 mb-5">
                    @foreach($project->media as $media)
                        <div class="col-6 col-lg-3">
                            <img loading="lazy" class="img-fluid rounded-3 w-100" style="height:180px;object-fit:cover;" src="{{ Storage::url($media->path) }}" alt="{{ $project->title }}">
                        </div>
                    @endforeach
                </div>
            @endif

            @if($project->products->count())
                <h2 class="h4 fw-semibold mb-3">Kullanılan Ürünler</h2>
                <div class="row g-4">
                    @foreach($project->products as $product)
                        <div class="col-12 col-md-6 col-lg-3">@include('public.partials.product-card', ['product' => $product])</div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
@endsection
