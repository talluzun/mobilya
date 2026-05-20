<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductPublicController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductPublicController::class, 'home'])->name('public.home');

Route::get('/katalog', [ProductPublicController::class, 'index'])->name('public.products.index');
Route::get('/urunler', fn () => redirect()->route('public.products.index'));
Route::get('/urun/{identifier}', [ProductPublicController::class, 'show'])->name('public.products.show');
Route::get('/katalog/{identifier}', [ProductPublicController::class, 'show'])->name('public.catalog.show');

Route::get('/projeler', [ProductPublicController::class, 'projects'])->name('public.projects.index');
Route::get('/projeler/{slug}', [ProductPublicController::class, 'projectShow'])->name('public.projects.show');

Route::get('/hakkimizda', [PageController::class, 'about'])->name('pages.about');
Route::get('/iletisim', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/iletisim', [PageController::class, 'storeContact'])->name('pages.contact.store');
Route::get('/ozel-siparis', [PageController::class, 'customOrder'])->name('pages.custom-order');
Route::post('/ozel-siparis', [PageController::class, 'storeCustomOrder'])->name('pages.custom-order.store');
Route::get('/gizlilik-politikasi', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/kullanim-sartlari', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/kvkk', [PageController::class, 'kvkk'])->name('pages.kvkk');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

Route::post('/teklifler', [QuoteController::class, 'store'])->name('quotes.store');
Route::get('/teklif/{refCode}', [PageController::class, 'quoteShow'])->name('public.quotes.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/kayit-ol', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/kayit-ol', [AuthController::class, 'register'])->name('register.store');
    Route::get('/giris', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/giris', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/cikis', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/hesabim/teklifler', [PageController::class, 'myQuotes'])->name('account.quotes.index');
    Route::get('/hesabim/teklifler/{quote}', [PageController::class, 'myQuote'])->name('account.quotes.show');
    Route::get('/hesabim/favoriler', [PageController::class, 'favorites'])->name('account.favorites');
    Route::post('/favoriler/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
