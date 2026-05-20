<footer class="bg-white border-top mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <h5 class="fw-semibold">{{ config('app.name') }}</h5>
                <p class="text-secondary mb-3">Mimari projeler, restoranlar, oteller ve özel mekanlar için seçilmiş sandalye koleksiyonları ve proje bazlı üretim çözümleri.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-dark btn-sm" href="https://wa.me/902125552424">WhatsApp</a>
                    <a class="btn btn-outline-dark btn-sm" href="tel:+902125552424">+90 (212) 555 24 24</a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="fw-semibold">Hızlı Linkler</h6>
                <ul class="list-unstyled">
                    <li><a class="text-secondary text-decoration-none" href="{{ route('public.products.index') }}">Katalog</a></li>
                    <li><a class="text-secondary text-decoration-none" href="{{ route('public.projects.index') }}">Projeler</a></li>
                    <li><a class="text-secondary text-decoration-none" href="{{ route('pages.contact') }}">İletişim</a></li>
                    <li><a class="text-secondary text-decoration-none" href="{{ route('pages.about') }}">Hakkımızda</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="fw-semibold">Belgeler</h6>
                <ul class="list-unstyled">
                    <li><a class="text-secondary text-decoration-none" href="{{ route('pages.privacy') }}">Gizlilik Politikası</a></li>
                    <li><a class="text-secondary text-decoration-none" href="{{ route('pages.terms') }}">Kullanım Şartları</a></li>
                    <li><a class="text-secondary text-decoration-none" href="{{ route('pages.kvkk') }}">KVKK / Aydınlatma Metni</a></li>
                </ul>
            </div>
            <div class="col-12 col-lg-3">
                <h6 class="fw-semibold">İletişim</h6>
                <ul class="list-unstyled text-secondary">
                    <li>info@mimaristsandalye.com</li>
                    <li>İstanbul, Türkiye</li>
                    <li>Hafta içi 09:00 - 18:00</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="border-top">
        <div class="container py-3 text-center text-secondary small">
            © {{ now()->year }} {{ config('app.name') }}. Tüm hakları saklıdır.
        </div>
    </div>
</footer>
