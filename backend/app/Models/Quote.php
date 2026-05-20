<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref_code',
        'product_id',
        'user_id',
        'selected_color_id',
        'selected_color_value_id',
        'selected_color_label',
        'customer_first_name',
        'customer_last_name',
        'customer_name',
        'customer_phone',
        'customer_email',
        'company_name',
        'note',
        'quantity',
        'base_price_snapshot',
        'color_price_snapshot',
        'options_price_snapshot',
        'total_price_snapshot',
        'status',
        'internal_note',
    ];

    protected $casts = [
        'base_price_snapshot' => 'decimal:2',
        'color_price_snapshot' => 'decimal:2',
        'options_price_snapshot' => 'decimal:2',
        'total_price_snapshot' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public const STATUSES = [
        'new' => 'Yeni',
        'reviewing' => 'İnceleniyor',
        'priced' => 'Fiyat Verildi',
        'approved' => 'Onaylandı',
        'rejected' => 'Reddedildi',
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

                if ($attempts >= 3 || ! str_contains($exception->getMessage(), 'ref_code')) {
                    throw $exception;
                }

                $this->ref_code = null;
            }
        }
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function selectedColorValue(): BelongsTo
    {
        return $this->belongsTo(ProductOptionValue::class, 'selected_color_value_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getCustomerFullNameAttribute(): string
    {
        return trim(($this->customer_first_name ?: '').' '.($this->customer_last_name ?: '')) ?: (string) $this->customer_name;
    }

    private function generateNextRefCode(Builder $query): string
    {
        $latest = $query->getConnection()
            ->table($this->getTable())
            ->lockForUpdate()
            ->whereYear('created_at', now()->year)
            ->orderBy('ref_code', 'desc')
            ->value('ref_code');

        $lastNumber = 0;

        if (is_string($latest) && preg_match('/^TKF-\d{4}-(\d+)$/', $latest, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return sprintf('TKF-%s-%04d', now()->year, $lastNumber + 1);
    }
}
