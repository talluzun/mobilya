<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductPublicController extends Controller
{
    public function home()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $categories = Schema::hasTable('categories')
            ? Category::query()->orderBy('name')->limit(6)->get()
            : collect();

        $projects = Schema::hasTable('projects')
            ? Project::query()->where('is_active', true)->orderBy('sort_order')->latest()->limit(3)->get()
            : collect();

        return view('welcome', compact('products', 'categories', 'projects'));
    }

    public function index(Request $request)
    {
        $categoryParam = $request->string('kategori')->toString();
        $activeCategory = null;

        $query = Product::query()
            ->where('is_active', true)
            ->with(['media', 'colorOption.values']);

        if (Schema::hasTable('categories')) {
            $categories = Category::query()
                ->orderBy('name')
                ->get();
        } else {
            $categories = collect();
        }

        if ($categoryParam !== '' && $categories->isNotEmpty()) {
            $activeCategory = $categories->firstWhere('slug', $categoryParam);
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->toString();
            $query->where('name', 'like', '%'.$search.'%');
        }

        $hasRoomType = Schema::hasColumn('products', 'room_type');
        $hasMaterial = Schema::hasColumn('products', 'material');

        if ($hasRoomType && $request->filled('mekan')) {
            $query->where('room_type', $request->string('mekan')->toString());
        }

        if ($hasMaterial && $request->filled('malzeme')) {
            $query->where('material', $request->string('malzeme')->toString());
        }

        if ($request->filled('renk')) {
            $color = $request->string('renk')->toString();
            $query->whereHas('options.values', function ($query) use ($color): void {
                $query->where('color_hex', $color);
            });
        }

        $sort = $request->string('sort')->toString();
        if ($sort === 'price_asc') {
            $query->orderBy('base_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('base_price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $activeCategorySlug = $activeCategory?->slug;
        $roomTypes = $hasRoomType ? Product::query()->whereNotNull('room_type')->distinct()->orderBy('room_type')->pluck('room_type') : collect();
        $materials = $hasMaterial ? Product::query()->whereNotNull('material')->distinct()->orderBy('material')->pluck('material') : collect();
        $colors = ProductOptionValue::query()
            ->whereNotNull('color_hex')
            ->select('label', 'color_hex')
            ->distinct()
            ->orderBy('label')
            ->get();

        return view('public.products-index', compact('products', 'categories', 'activeCategorySlug', 'roomTypes', 'materials', 'colors'));
    }

    public function show(string $identifier)
    {
        $product = Product::query()
            ->where(function ($query) use ($identifier): void {
                $query->where('ref_code', $identifier)->orWhere('slug', $identifier);
            })
            ->where('is_active', true)
            ->with(['media', 'options.values'])
            ->firstOrFail();

        return view('public.product-show', compact('product'));
    }

    public function projects()
    {
        $projects = Project::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function projectShow(string $slug)
    {
        $project = Project::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['media', 'products.media'])
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }
}
