@extends('layouts.public')

@section('title', 'Projeler | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="mb-4">
                <h1 class="h2 fw-semibold">Projeler</h1>
                <p class="text-secondary">Mekanlara göre uygulanmış sandalye ve oturma çözümleri.</p>
            </div>
            <div class="row g-4">
                @forelse($projects as $project)
                    <div class="col-12 col-md-6 col-lg-4">
                        <a class="card h-100 border-0 shadow-sm text-decoration-none text-dark" href="{{ route('public.projects.show', $project->slug) }}">
                            <img loading="lazy" class="card-img-top" style="height:240px;object-fit:cover;" src="{{ $project->cover_image ? Storage::url($project->cover_image) : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $project->title }}">
                            <div class="card-body">
                                <h5>{{ $project->title }}</h5>
                                <p class="text-secondary mb-0">{{ collect([$project->city, $project->venue_type])->filter()->join(' · ') }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-light border">Henüz proje eklenmedi.</div></div>
                @endforelse
            </div>
            <div class="mt-4">{{ $projects->links('pagination::bootstrap-5') }}</div>
        </div>
    </main>
@endsection
