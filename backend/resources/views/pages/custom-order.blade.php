@extends('layouts.public')

@section('title', 'Özel Sipariş | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h1 class="h2 fw-semibold">Özel Sipariş Formu</h1>
                    <p class="text-secondary">Ölçü, adet, renk ve referans bilgilerinizi paylaşın; proje ekibimiz size dönüş yapsın.</p>
                    <form class="bg-white border rounded-3 p-4" method="post" action="{{ route('pages.custom-order.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Ad Soyad</label><input class="form-control" name="full_name" value="{{ old('full_name', auth()->user()?->name) }}" required></div>
                            <div class="col-md-6"><label class="form-label">Telefon</label><input class="form-control" name="phone" required></div>
                            <div class="col-md-6"><label class="form-label">E-posta</label><input class="form-control" type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required></div>
                            <div class="col-md-6"><label class="form-label">Şirket Adı</label><input class="form-control" name="company_name"></div>
                            <div class="col-md-6"><label class="form-label">Ürün Tipi</label><input class="form-control" name="product_type" placeholder="Sandalye, bar sandalyesi..." required></div>
                            <div class="col-md-3"><label class="form-label">Ölçü</label><input class="form-control" name="measurements"></div>
                            <div class="col-md-3"><label class="form-label">Adet</label><input class="form-control" type="number" name="quantity" min="1" value="1" required></div>
                            <div class="col-md-6"><label class="form-label">Renk/Kaplama İsteği</label><input class="form-control" name="color_request"></div>
                            <div class="col-md-6"><label class="form-label">Referans Görsel</label><input class="form-control" type="file" name="reference_image" accept="image/*"></div>
                            <div class="col-12"><label class="form-label">Açıklama</label><textarea class="form-control" name="description" rows="5"></textarea></div>
                        </div>
                        @if($errors->any())<div class="alert alert-danger mt-3">{{ $errors->first() }}</div>@endif
                        <button class="btn btn-bordeaux mt-3" type="submit">Talep Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
