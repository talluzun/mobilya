<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta_description', 'Mimarist Sandalye; profesyonel projeler için sandalye katalog ve teklif talep platformu.')">
        <title>@yield('title', config('app.name'))</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            :root {
                --ms-cream: #f7f3ee;
                --ms-ink: #232323;
                --ms-bordeaux: #5f1724;
                --ms-muted: #6f6a64;
            }
            body { background: var(--ms-cream); color: var(--ms-ink); }
            .btn-bordeaux { --bs-btn-bg: var(--ms-bordeaux); --bs-btn-border-color: var(--ms-bordeaux); --bs-btn-color: #fff; --bs-btn-hover-bg: #43111a; --bs-btn-hover-border-color: #43111a; }
            .text-bordeaux { color: var(--ms-bordeaux); }
            .bg-ink { background: var(--ms-ink); }
            .hero-media { min-height: 420px; object-fit: cover; }
            .product-main-image { height: 420px; object-fit: cover; }
            .product-thumb { width: 86px; height: 86px; object-fit: cover; cursor: pointer; border: 2px solid transparent; }
            .product-thumb.active { border-color: var(--ms-bordeaux); }
            .swatch { width: 28px; height: 28px; border-radius: 50%; border: 2px solid #ddd; display: inline-block; vertical-align: middle; }
            .swatch-button.active .swatch, .option-card.active { border-color: var(--ms-bordeaux); }
            .option-card { border: 1px solid #ddd; background: #fff; border-radius: 8px; padding: .6rem .8rem; cursor: pointer; }
            .page-band { background: #fff; border-block: 1px solid rgba(0,0,0,.06); }
        </style>
    </head>
    <body>
        @include('public.partials.navbar')

        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success mb-0">{{ session('success') }}</div>
            </div>
        @endif

        @yield('content')

        @include('public.partials.footer')

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
