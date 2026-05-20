@extends('layouts.public')

@section('title', 'Tekliflerim | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="d-flex gap-3 mb-4">
                <a class="btn btn-bordeaux" href="{{ route('account.quotes.index') }}">Tekliflerim</a>
                <a class="btn btn-outline-dark" href="{{ route('account.favorites') }}">Favorilerim</a>
            </div>
            <div class="bg-white border rounded-3 p-4">
                <h1 class="h3 fw-semibold mb-3">Tekliflerim</h1>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Kod</th><th>Ürün</th><th>Durum</th><th>Tarih</th><th></th></tr></thead>
                        <tbody>
                            @forelse($quotes as $quote)
                                <tr>
                                    <td>{{ $quote->ref_code }}</td>
                                    <td>{{ $quote->product?->name }}</td>
                                    <td>{{ $quote->status_label }}</td>
                                    <td>{{ $quote->created_at->format('d.m.Y') }}</td>
                                    <td><a class="btn btn-outline-dark btn-sm" href="{{ route('account.quotes.show', $quote) }}">Detay</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-secondary">Henüz teklif talebiniz yok.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $quotes->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </main>
@endsection
