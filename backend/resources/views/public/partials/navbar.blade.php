<header class="border-bottom bg-white sticky-top">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container py-2">
            <a class="navbar-brand fw-bold text-uppercase" href="{{ route('public.home') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menüyü Aç">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-lg-auto gap-lg-3">
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.products.index') }}">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('public.projects.index') }}">Projeler</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pages.contact') }}">İletişim</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pages.about') }}">Hakkımızda</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a class="btn btn-bordeaux btn-sm px-3" href="{{ route('pages.custom-order') }}">Özel Sipariş</a>
                    @auth
                        <a class="text-decoration-none text-dark" href="{{ route('account.quotes.index') }}">Hesabım</a>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-link text-dark text-decoration-none p-0" type="submit">Çıkış</button>
                        </form>
                    @else
                        <a class="text-decoration-none text-dark" href="{{ route('register') }}">Kayıt Ol</a>
                        <a class="text-decoration-none text-dark" href="{{ route('login') }}">Giriş Yap</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
