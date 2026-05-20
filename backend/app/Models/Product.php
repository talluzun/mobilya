<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'ref_code',
        'category_id',
        'category',
        'room_type',
        'material',
        'is_active',
        'thumbnail_path',
        'description',
        'features',
        'care_instructions',
        'warranty_info',
        'base_price',
        'shipping_cost',
        'delivery_time',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    protected function performInsert(Builder $query): bool
    {
        $attempts = 0;

        while (true) {
            try {
                return DB::transaction(function () use ($query): bool {
                    if (empty($this->ref_code)) {
                        $this->ref_code = $this->generateNextRefCode($query);
                    }

                    return parent::performInsert($query);
                }, 3);
            } catch (QueryException $exception) {
                $attempts++;

                if ($attempts >= 3 || ! $this->isRefCodeDuplicate($exception)) {
                    throw $exception;
                }

                $this->ref_code = null;
            }
        }
    }

    public function duplicate(): self
    {
        return DB::transaction(function (): self {
            $this->loadMissing(['media', 'options.values']);

            $duplicate = $this->replicate(['ref_code', 'slug']);
            $duplicate->name = $this->name.' Kopya';
            $duplicate->slug = $this->generateUniqueSlug($duplicate->name);
            $duplicate->ref_code = null;
            $duplicate->save();

            foreach ($this->media as $media) {
                $duplicate->media()->create([
                    'path' => $media->path,
                    'sort_order' => $media->sort_order,
                ]);
            }

            foreach ($this->options as $option) {
                $newOption = $duplicate->options()->create([
                    'label' => $option->label,
                    'key' => $option->key,
                    'type' => $option->type,
                    'is_required' => $option->is_required,
                ]);

                foreach ($option->values as $value) {
                    $newOption->values()->create([
                        'label' => $value->label,
                        'color_hex' => $value->color_hex,
                        'price_delta' => $value->price_delta,
                        'is_default' => $value->is_default,
                    ]);
                }
            }

            return $duplicate;
        });
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function colorOption(): HasOne
    {
        return $this->hasOne(ProductOption::class)->where('key', 'color');
    }

    public function extraOptions(): HasMany
    {
        return $this->hasMany(ProductOption::class)->where('key', '!=', 'color');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function deleteWithRelations(): string
    {
        return DB::transaction(function (): string {
            if ($this->quotes()->exists()) {
                $this->delete();
                return 'soft';
            }

            $this->forceDelete();

            return 'force';
        });
    }

    private function generateNextRefCode(Builder $query): string
    {
        $connection = $query->getConnection();

        $latest = $connection->table($this->getTable())
            ->lockForUpdate()
            ->orderBy('ref_code', 'desc')
            ->value('ref_code');

        $lastNumber = 0;

        if (is_string($latest) && Str::startsWith($latest, 'MS')) {
            $lastNumber = (int) substr($latest, 2);
        }

        return sprintf('MS%06d', $lastNumber + 1);
    }

    private function isRefCodeDuplicate(QueryException $exception): bool
    {
        $message = $exception->getMessage();

        return str_contains($message, 'ref_code');
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 2;

        while (self::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    protected static function booted(): void
    {
        static::forceDeleting(function (Product $product): void {
            $product->loadMissing(['media', 'options']);

            if ($product->thumbnail_path) {
                Storage::disk('public')->delete($product->thumbnail_path);
            }

            foreach ($product->media as $media) {
                Storage::disk('public')->delete($media->path);
            }

            $product->media()->delete();
            $product->options()->delete();
        });
    }
}
