@extends('layouts.public')

@section('title', $product->name.' | '.config('app.name'))
@section('meta_description', Str::limit(strip_tags($product->description ?: $product->name), 155))

@section('content')
    <main class="py-5">
        <div class="container">
            @php
                $imagePath = $product->thumbnail_path
                    ? Storage::url($product->thumbnail_path)
                    : ($product->media->first()? Storage::url($product->media->first()->path) : 'https://via.placeholder.com/900x700');
                $galleryImages = $product->media->map(fn ($media) => Storage::url($media->path))->all();
                $formatCurrency = fn ($amount) => '₺'.number_format((float) $amount, 2, ',', '.');
            @endphp

            <div class="row g-5 align-items-start">
                <div class="col-lg-6">
                    <div class="bg-white rounded-3 shadow-sm p-3">
                        <img id="mainProductImage" src="{{ $imagePath }}" loading="lazy" class="img-fluid rounded-3 w-100 product-main-image" alt="{{ $product->name }}">
                        @if(count($galleryImages) > 0)
                            <div class="d-flex gap-3 mt-3 flex-wrap">
                                @foreach($galleryImages as $index => $thumb)
                                    <img src="{{ $thumb }}" loading="lazy" data-full="{{ $thumb }}" class="rounded-3 product-thumb {{ $index === 0 ? 'active' : '' }}" alt="{{ $product->name }} küçük görsel">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="badge rounded-pill text-bg-light border mb-3">{{ collect([$product->category, $product->room_type, $product->material])->filter()->join(' · ') }}</span>
                    <h1 class="h3 mb-3">{{ $product->name }}</h1>
                    @if($product->description)
                        <div class="text-secondary mb-4">{!! $product->description !!}</div>
                    @endif

                    @auth
                        <form method="post" action="{{ route('favorites.toggle', $product) }}" class="mb-3">
                            @csrf
                            <button class="btn btn-outline-dark" type="submit">Favorilere Ekle / Kaldır</button>
                        </form>
                    @else
                        <a class="btn btn-outline-dark mb-3" href="{{ route('login') }}">Favorilere eklemek için giriş yap</a>
                    @endauth

                    <div class="bg-white border rounded-3 p-4 mb-4">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="text-secondary small">Başlangıç fiyatı</div>
                                <div class="h5 mb-0">{{ $product->base_price ? $formatCurrency($product->base_price) : 'Teklif ile belirlenecek' }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-secondary small">Kargo</div>
                                <div class="h6 mb-0">
                                    @if($product->shipping_cost !== null)
                                        {{ (float) $product->shipping_cost === 0.0 ? 'Ücretsiz Kargo' : $formatCurrency($product->shipping_cost) }}
                                    @else
                                        Kargo bedeli teklif sırasında belirlenecek.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('quotes.store') }}" class="bg-white border rounded-3 p-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        @if($product->colorOption && $product->colorOption->values->count())
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Renk Seçimi</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    @foreach($product->colorOption->values as $index => $color)
                                        <label class="option-card swatch-button {{ $index === 0 ? 'active' : '' }}">
                                            <input class="d-none option-input" type="radio" name="selected_color_value_id" value="{{ $color->id }}" @checked($index === 0)>
                                            <span class="swatch me-2" style="background-color: {{ $color->color_hex }}"></span>{{ $color->label }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @foreach($product->extraOptions as $option)
                            @if($option->values->count())
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">{{ $option->label }}</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($option->values as $valueIndex => $value)
                                            <label class="option-card {{ $valueIndex === 0 ? 'active' : '' }}">
                                                <input class="d-none option-input" type="radio" name="option_values[{{ $option->id }}]" value="{{ $value->id }}" @checked($valueIndex === 0)>
                                                @if($value->color_hex)
                                                    <span class="swatch me-2" style="width:18px;height:18px;background-color: {{ $value->color_hex }}"></span>
                                                @endif
                                                {{ $value->label }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Adet</label>
                                <input class="form-control" type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ad</label>
                                <input class="form-control" name="customer_first_name" value="{{ old('customer_first_name', auth()->user()?->name) }}" @guest required @endguest>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Soyad</label>
                                <input class="form-control" name="customer_last_name" value="{{ old('customer_last_name') }}" @guest required @endguest>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-posta</label>
                                <input class="form-control" type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" @guest required @endguest>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input class="form-control" name="customer_phone" value="{{ old('customer_phone') }}" @guest required @endguest>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Şirket Adı</label>
                                <input class="form-control" name="company_name" value="{{ old('company_name') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mesaj / Not</label>
                                <textarea class="form-control" name="note" rows="3">{{ old('note') }}</textarea>
                            </div>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
                        @endif
                        <button class="btn btn-bordeaux btn-lg w-100 mt-4" type="submit">Teklif Talep Et</button>
                    </form>
                </div>
            </div>

            <div class="mt-5 bg-white border rounded-3 p-4">
                <ul class="nav nav-pills gap-2 mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#desc" type="button">Açıklama</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#features" type="button">Özellikler</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#care" type="button">Bakım</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#warranty" type="button">Garanti</button></li>
                </ul>
                <div class="tab-content">
                    <div id="desc" class="tab-pane fade show active">{!! $product->description ?: 'Detaylı açıklama yakında.' !!}</div>
                    <div id="features" class="tab-pane fade">{!! nl2br(e($product->features ?: 'Özellik bilgileri yakında.')) !!}</div>
                    <div id="care" class="tab-pane fade">{!! nl2br(e($product->care_instructions ?: 'Bakım bilgileri yakında.')) !!}</div>
                    <div id="warranty" class="tab-pane fade">{!! nl2br(e($product->warranty_info ?: 'Garanti bilgileri yakında.')) !!}</div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.product-thumb').forEach((thumb) => {
            thumb.addEventListener('click', () => {
                document.querySelectorAll('.product-thumb').forEach((item) => item.classList.remove('active'));
                thumb.classList.add('active');
                document.getElementById('mainProductImage').src = thumb.dataset.full;
            });
        });

        document.querySelectorAll('.option-card input[type="radio"]').forEach((input) => {
            input.addEventListener('change', () => {
                document.querySelectorAll(`input[name="${input.name}"]`).forEach((groupInput) => {
                    groupInput.closest('.option-card').classList.remove('active');
                });
                input.closest('.option-card').classList.add('active');
            });
        });
    </script>
@endpush
