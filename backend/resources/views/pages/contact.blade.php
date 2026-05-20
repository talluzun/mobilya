@extends('layouts.public')

@section('title', 'İletişim | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <h1 class="h2 fw-semibold">İletişim</h1>
                    <p class="text-secondary">Proje, katalog ve teklif sorularınız için bize ulaşın.</p>
                    <div class="bg-white border rounded-3 p-4">
                        <p><strong>WhatsApp:</strong> <a href="https://wa.me/902125552424">+90 (212) 555 24 24</a></p>
                        <p><strong>Telefon:</strong> <a href="tel:+902125552424">+90 (212) 555 24 24</a></p>
                        <p><strong>E-posta:</strong> info@mimaristsandalye.com</p>
                        <p><strong>Adres:</strong> İstanbul, Türkiye</p>
                        <div class="bg-light border rounded-3 p-5 text-center text-secondary">Google Maps alanı</div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form class="bg-white border rounded-3 p-4" method="post" action="{{ route('pages.contact.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Ad Soyad</label><input class="form-control" name="full_name" required></div>
                            <div class="col-md-6"><label class="form-label">E-posta</label><input class="form-control" type="email" name="email" required></div>
                            <div class="col-md-6"><label class="form-label">Telefon</label><input class="form-control" name="phone"></div>
                            <div class="col-md-6"><label class="form-label">Konu</label><input class="form-control" name="subject"></div>
                            <div class="col-12"><label class="form-label">Mesaj</label><textarea class="form-control" name="message" rows="5" required></textarea></div>
                        </div>
                        <button class="btn btn-bordeaux mt-3" type="submit">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
