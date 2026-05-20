<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'label',
        'key',
        'type',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProductOption $option): void {
            if (! $option->key && $option->label && $option->product_id) {
                $option->key = $option->generateUniqueKey();
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class)->orderBy('id');
    }

    private function generateUniqueKey(): string
    {
        $base = Str::snake($this->label);

        if ($base === '') {
            $base = 'option';
        }

        $query = self::query()->where('product_id', $this->product_id);

        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        $existing = $query->pluck('key')->all();
        $candidate = $base;
        $suffix = 2;

        while (in_array($candidate, $existing, true)) {
            $candidate = $base.'_'.$suffix;
            $suffix++;
        }

        return $candidate;
    }
}
