@extends('layouts.public')

@section('title', 'Katalog | '.config('app.name'))
@section('meta_description', 'Mimarist Sandalye katalog ürünleri, kategori, mekan tipi, malzeme ve renk filtreleri.')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="mb-4">
                <h1 class="h2 fw-semibold">Katalog</h1>
                <p class="text-secondary mb-0">{{ $products->total() }} aktif ürün listeleniyor.</p>
            </div>
            <div class="row g-4">
                <aside class="col-12 col-lg-3">
                    <form class="bg-white border rounded-3 p-4" method="get">
                        <div class="mb-3">
                            <label class="form-label">Arama</label>
                            <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Ürün ara">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori">
                                <option value="">Tümü</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" @selected($activeCategorySlug === $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mekan Tipi</label>
                            <select class="form-select" name="mekan">
                                <option value="">Tümü</option>
                                @foreach($roomTypes as $roomType)
                                    <option value="{{ $roomType }}" @selected(request('mekan') === $roomType)>{{ $roomType }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Malzeme</label>
                            <select class="form-select" name="malzeme">
                                <option value="">Tümü</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material }}" @selected(request('malzeme') === $material)>{{ $material }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Renk</label>
                            <select class="form-select" name="renk">
                                <option value="">Tümü</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->color_hex }}" @selected(request('renk') === $color->color_hex)>{{ $color->label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sıralama</label>
                            <select name="sort" class="form-select">
                                <option value="">Varsayılan</option>
                                <option value="newest" @selected(request('sort') === 'newest')>En Yeni</option>
                                <option value="price_asc" @selected(request('sort') === 'price_asc')>Fiyat Artan</option>
                                <option value="price_desc" @selected(request('sort') === 'price_desc')>Fiyat Azalan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-bordeaux w-100">Filtrele</button>
                    </form>
                </aside>
                <section class="col-12 col-lg-9">
                    <div class="row g-4">
                        @forelse($products as $product)
                            <div class="col-12 col-md-6 col-xl-4">@include('public.partials.product-card', ['product' => $product])</div>
                        @empty
                            <div class="col-12"><div class="alert alert-light border text-center mb-0">Filtrelere uygun ürün bulunamadı.</div></div>
                        @endforelse
                    </div>
                    <div class="mt-4">{{ $products->links('pagination::bootstrap-5') }}</div>
                </section>
            </div>
        </div>
    </main>
@endsection
