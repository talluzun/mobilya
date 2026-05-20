<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        if ($user->favoriteProducts()->whereKey($product->id)->exists()) {
            $user->favoriteProducts()->detach($product->id);
            return back()->with('success', 'Ürün favorilerden kaldırıldı.');
        }

        $user->favoriteProducts()->attach($product->id);

        return back()->with('success', 'Ürün favorilere eklendi.');
    }
}
