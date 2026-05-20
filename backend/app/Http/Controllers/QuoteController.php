<?php

namespace App\Http\Controllers;

use App\Mail\NewQuoteRequestMail;
use App\Models\Product;
use App\Models\ProductOptionValue;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class QuoteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'selected_color_value_id' => ['nullable', 'exists:product_option_values,id'],
            'option_values' => ['nullable', 'array'],
            'option_values.*' => ['integer', 'exists:product_option_values,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:9999'],
            'customer_first_name' => [$user ? 'nullable' : 'required', 'string', 'max:255'],
            'customer_last_name' => [$user ? 'nullable' : 'required', 'string', 'max:255'],
            'customer_email' => [$user ? 'nullable' : 'required', 'email', 'max:255'],
            'customer_phone' => [$user ? 'nullable' : 'required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:5000'],
        ]);

        $product = Product::query()
            ->with(['colorOption.values', 'extraOptions.values'])
            ->findOrFail($validated['product_id']);

        $selectedColor = null;
        if (! empty($validated['selected_color_value_id'])) {
            $selectedColor = $product->colorOption?->values->firstWhere('id', (int) $validated['selected_color_value_id']);

            if (! $selectedColor) {
                throw ValidationException::withMessages([
                    'selected_color_value_id' => 'Seçilen renk bu ürüne ait değil.',
                ]);
            }
        }

        $optionValueIds = array_values(array_unique(array_map('intval', $validated['option_values'] ?? [])));
        $selectedValues = collect();

        if ($optionValueIds !== []) {
            $selectedValues = ProductOptionValue::query()
                ->with('option')
                ->whereIn('id', $optionValueIds)
                ->get();

            $invalidSelection = $selectedValues->count() !== count($optionValueIds)
                || $selectedValues->contains(fn (ProductOptionValue $value): bool => $value->option?->product_id !== $product->id);

            if ($invalidSelection) {
                throw ValidationException::withMessages([
                    'option_values' => 'Seçilen opsiyonlardan biri bu ürüne ait değil.',
                ]);
            }
        }

        $quantity = (int) $validated['quantity'];
        $basePrice = (float) ($product->base_price ?? 0);
        $colorPrice = (float) ($selectedColor?->price_delta ?? 0);
        $optionsPrice = (float) $selectedValues->sum(fn (ProductOptionValue $value): float => (float) ($value->price_delta ?? 0));
        $totalPrice = ($basePrice + $colorPrice + $optionsPrice) * $quantity;

        $quote = DB::transaction(function () use ($validated, $user, $product, $selectedColor, $selectedValues, $quantity, $basePrice, $colorPrice, $optionsPrice, $totalPrice): Quote {
            $firstName = $validated['customer_first_name'] ?? $user?->name;
            $lastName = $validated['customer_last_name'] ?? null;

            $quote = Quote::create([
                'product_id' => $product->id,
                'user_id' => $user?->id,
                'selected_color_value_id' => $selectedColor?->id,
                'selected_color_label' => $selectedColor?->label,
                'customer_first_name' => $firstName,
                'customer_last_name' => $lastName,
                'customer_name' => trim(($firstName ?? '').' '.($lastName ?? '')),
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? $user?->email,
                'company_name' => $validated['company_name'] ?? null,
                'note' => $validated['note'] ?? null,
                'quantity' => $quantity,
                'base_price_snapshot' => $basePrice,
                'color_price_snapshot' => $colorPrice,
                'options_price_snapshot' => $optionsPrice,
                'total_price_snapshot' => $totalPrice,
                'status' => 'new',
            ]);

            foreach ($selectedValues as $value) {
                $option = $value->option;

                if (! $option) {
                    throw ValidationException::withMessages([
                        'option_values' => 'Seçilen opsiyon değeri geçerli bir opsiyona bağlı değil.',
                    ]);
                }

                $quote->items()->create([
                    'option_id' => $option->id,
                    'option_value_id' => $value->id,
                    'option_label_snapshot' => $option->label,
                    'value_label_snapshot' => $value->label,
                    'option_price_snapshot' => (float) ($value->price_delta ?? 0),
                ]);
            }

            return $quote->load(['product', 'items']);
        });

        $this->notifyAdmin($quote);

        return redirect()
            ->route('public.quotes.show', $quote->ref_code)
            ->with('success', 'Teklif talebiniz alındı. Teklif kodunuz: '.$quote->ref_code);
    }

    private function notifyAdmin(Quote $quote): void
    {
        $recipient = config('quotes.notification_email');

        if (! $recipient) {
            return;
        }

        try {
            Mail::to($recipient)->send(new NewQuoteRequestMail($quote));
        } catch (\Throwable $exception) {
            Log::warning('Quote notification mail could not be sent.', [
                'quote_id' => $quote->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
