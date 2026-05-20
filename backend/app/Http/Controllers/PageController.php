<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\CustomOrder;
use App\Models\Product;
use App\Models\Project;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function storeContact(Request $request): RedirectResponse
    {
        ContactMessage::create($request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]));

        return back()->with('success', 'Mesajınız alındı. En kısa sürede dönüş yapacağız.');
    }

    public function customOrder(): View
    {
        return view('pages.custom-order');
    }

    public function storeCustomOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'product_type' => ['required', 'string', 'max:255'],
            'measurements' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1', 'max:9999'],
            'color_request' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'reference_image' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('reference_image')) {
            $validated['reference_image'] = $request->file('reference_image')->store('custom-orders', 'public');
        }

        $validated['user_id'] = $request->user()?->id;
        CustomOrder::create($validated);

        return back()->with('success', 'Özel sipariş talebiniz alındı.');
    }

    public function quoteShow(string $refCode): View
    {
        $quote = Quote::query()
            ->with(['product', 'items'])
            ->where('ref_code', $refCode)
            ->firstOrFail();

        return view('quotes.show', compact('quote'));
    }

    public function myQuotes(Request $request): View
    {
        $quotes = $request->user()
            ->quotes()
            ->with('product')
            ->latest()
            ->paginate(12);

        return view('account.quotes-index', compact('quotes'));
    }

    public function myQuote(Request $request, Quote $quote): View
    {
        abort_unless($quote->user_id === $request->user()->id, 403);

        $quote->load(['product', 'items']);

        return view('account.quote-show', compact('quote'));
    }

    public function favorites(Request $request): View
    {
        $products = $request->user()
            ->favoriteProducts()
            ->with('media')
            ->paginate(12);

        return view('account.favorites', compact('products'));
    }

    public function privacy(): View
    {
        return view('pages.legal', ['title' => 'Gizlilik Politikası']);
    }

    public function terms(): View
    {
        return view('pages.legal', ['title' => 'Kullanım Şartları']);
    }

    public function kvkk(): View
    {
        return view('pages.legal', ['title' => 'KVKK / Aydınlatma Metni']);
    }

    public function sitemap(): Response
    {
        $urls = collect([
            route('public.home'),
            route('public.products.index'),
            route('public.projects.index'),
            route('pages.about'),
            route('pages.contact'),
            route('pages.custom-order'),
        ]);

        Product::query()->where('is_active', true)->pluck('slug')->each(
            fn (string $slug) => $urls->push(route('public.catalog.show', $slug))
        );

        if (Schema::hasTable('projects')) {
            Project::query()->where('is_active', true)->pluck('slug')->each(
                fn (string $slug) => $urls->push(route('public.projects.show', $slug))
            );
        }

        return response()
            ->view('sitemap', ['urls' => $urls], 200)
            ->header('Content-Type', 'application/xml');
    }

    public function latestProjects(int $limit = 3)
    {
        if (! Schema::hasTable('projects')) {
            return collect();
        }

        return Project::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
