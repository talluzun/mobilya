@php
    $imagePath = $product->thumbnail_path
        ? \Illuminate\Support\Facades\Storage::url($product->thumbnail_path)
        : ($product->media->first()? \Illuminate\Support\Facades\Storage::url($product->media->first()->path) : 'https://via.placeholder.com/600x450');
@endphp

<a href="{{ route('public.products.show', $product->ref_code) }}" class="text-decoration-none text-dark h-100">
    <div class="card h-100 border-0 shadow-sm">
        <img src="{{ $imagePath }}" loading="lazy" class="card-img-top" alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
        <div class="card-body">
            <h6 class="card-title mb-2">{{ $product->name }}</h6>
            @if($product->room_type || $product->material)
                <p class="text-secondary small mb-2">{{ collect([$product->room_type, $product->material])->filter()->join(' · ') }}</p>
            @endif
            @if($product->delivery_time)
                <p class="text-secondary small mb-3">Teslimat: {{ $product->delivery_time }}</p>
            @endif
            <span class="btn btn-outline-dark btn-sm">Teklif Al</span>
        </div>
    </div>
</a>
