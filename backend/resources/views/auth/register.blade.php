@extends('layouts.public')

@section('title', 'Kayıt Ol | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <form class="bg-white border rounded-3 p-4" method="post" action="{{ route('register.store') }}">
                        @csrf
                        <h1 class="h3 fw-semibold mb-3">Kayıt Ol</h1>
                        <div class="mb-3"><label class="form-label">Ad Soyad</label><input class="form-control" name="name" value="{{ old('name') }}" required></div>
                        <div class="mb-3"><label class="form-label">E-posta</label><input class="form-control" type="email" name="email" value="{{ old('email') }}" required></div>
                        <div class="mb-3"><label class="form-label">Şifre</label><input class="form-control" type="password" name="password" required></div>
                        <div class="mb-3"><label class="form-label">Şifre Tekrar</label><input class="form-control" type="password" name="password_confirmation" required></div>
                        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                        <button class="btn btn-bordeaux w-100" type="submit">Kayıt Ol</button>
                        <p class="text-secondary small mt-3 mb-0">Zaten hesabınız var mı? <a href="{{ route('login') }}">Giriş yapın</a></p>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
