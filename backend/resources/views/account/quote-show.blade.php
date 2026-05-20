@extends('layouts.public')

@section('title', $quote->ref_code.' | Teklif Detayı')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="bg-white border rounded-3 p-4">
                <h1 class="h3 fw-semibold">{{ $quote->ref_code }}</h1>
                <p class="text-secondary">{{ $quote->created_at->format('d.m.Y H:i') }} · {{ $quote->status_label }}</p>
                <p><strong>Ürün:</strong> {{ $quote->product?->name }}</p>
                <p><strong>Seçilen renk:</strong> {{ $quote->selected_color_label ?: '-' }}</p>
                <p><strong>Adet:</strong> {{ $quote->quantity }}</p>
                @if($quote->items->count())
                    <h2 class="h5 mt-4">Opsiyonlar</h2>
                    <ul>
                        @foreach($quote->items as $item)
                            <li>{{ $item->option_label_snapshot }}: {{ $item->value_label_snapshot }}</li>
                        @endforeach
                    </ul>
                @endif
                @if($quote->note)
                    <p><strong>Mesaj:</strong> {{ $quote->note }}</p>
                @endif
            </div>
        </div>
    </main>
@endsection
