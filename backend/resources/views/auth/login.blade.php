@extends('layouts.public')

@section('title', 'Giriş Yap | '.config('app.name'))

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <form class="bg-white border rounded-3 p-4" method="post" action="{{ route('login.store') }}">
                        @csrf
                        <h1 class="h3 fw-semibold mb-3">Giriş Yap</h1>
                        <div class="mb-3"><label class="form-label">E-posta</label><input class="form-control" type="email" name="email" value="{{ old('email') }}" required></div>
                        <div class="mb-3"><label class="form-label">Şifre</label><input class="form-control" type="password" name="password" required></div>
                        <label class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember"> <span class="form-check-label">Beni hatırla</span></label>
                        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                        <button class="btn btn-bordeaux w-100" type="submit">Giriş Yap</button>
                        <p class="text-secondary small mt-3 mb-0">Hesabınız yok mu? <a href="{{ route('register') }}">Kayıt olun</a></p>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
