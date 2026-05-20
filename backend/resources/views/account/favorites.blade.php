@extends('layouts.public')

@section('title', 'Favorilerim | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="d-flex gap-3 mb-4">
                <a class="btn btn-outline-dark" href="{{ route('account.quotes.index') }}">Tekliflerim</a>
                <a class="btn btn-bordeaux" href="{{ route('account.favorites') }}">Favorilerim</a>
            </div>
            <h1 class="h3 fw-semibold mb-3">Favorilerim</h1>
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-12 col-md-6 col-lg-3">@include('public.partials.product-card', ['product' => $product])</div>
                @empty
                    <div class="col-12"><div class="alert alert-light border">Henüz favori ürününüz yok.</div></div>
                @endforelse
            </div>
            <div class="mt-4">{{ $products->links('pagination::bootstrap-5') }}</div>
        </div>
    </main>
@endsection
