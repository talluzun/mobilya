@extends('layouts.public')

@section('title', $title.' | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="bg-white border rounded-3 p-4 p-lg-5">
                <h1 class="h2 fw-semibold">{{ $title }}</h1>
                <p class="text-secondary mb-0">Bu sayfa için yasal metin içerikleri yönetim sürecinde eklenecektir. {{ config('app.name') }} kullanıcı verilerini yalnızca teklif, iletişim ve proje süreçlerini yürütmek için işler.</p>
            </div>
        </div>
    </main>
@endsection
