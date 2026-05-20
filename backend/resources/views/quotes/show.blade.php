@extends('layouts.public')

@section('title', $quote->ref_code.' | Teklif Talebi')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="bg-white border rounded-3 p-4 p-lg-5">
                <h1 class="h3 fw-semibold">Teklif Talebiniz Alındı</h1>
                <p class="text-secondary">Teklif kodunuz: <strong>{{ $quote->ref_code }}</strong></p>
                <div class="row g-3">
                    <div class="col-md-6"><strong>Ürün:</strong> {{ $quote->product?->name }}</div>
                    <div class="col-md-6"><strong>Durum:</strong> {{ $quote->status_label }}</div>
                    <div class="col-md-6"><strong>Adet:</strong> {{ $quote->quantity }}</div>
                    <div class="col-md-6"><strong>Tarih:</strong> {{ $quote->created_at->format('d.m.Y H:i') }}</div>
                </div>
                <a class="btn btn-bordeaux mt-4" href="{{ route('public.products.index') }}">Kataloğa Dön</a>
            </div>
        </div>
    </main>
@endsection
