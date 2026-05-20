<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasProductColors = Schema::hasTable('product_colors');

        if (!Schema::hasTable('quotes')) {
            Schema::create('quotes', function (Blueprint $table) use ($hasProductColors): void {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->unsignedBigInteger('selected_color_id')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('customer_email')->nullable();
                $table->text('note')->nullable();
                $table->decimal('base_price_snapshot', 10, 2);
                $table->decimal('color_price_snapshot', 10, 2)->default(0);
                $table->decimal('options_price_snapshot', 10, 2)->default(0);
                $table->decimal('total_price_snapshot', 10, 2);
                $table->string('status')->default('pending');
                $table->timestamps();

                if ($hasProductColors) {
                    $table->foreign('selected_color_id')
                        ->references('id')
                        ->on('product_colors')
                        ->nullOnDelete();
                }
            });
        } else {
            Schema::table('quotes', function (Blueprint $table): void {
                if (!Schema::hasColumn('quotes', 'product_id')) {
                    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                }
                if (!Schema::hasColumn('quotes', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                }
                if (!Schema::hasColumn('quotes', 'selected_color_id')) {
                    $table->unsignedBigInteger('selected_color_id')->nullable();
                }
                if (!Schema::hasColumn('quotes', 'customer_name')) {
                    $table->string('customer_name')->nullable();
                }
                if (!Schema::hasColumn('quotes', 'customer_phone')) {
                    $table->string('customer_phone')->nullable();
                }
                if (!Schema::hasColumn('quotes', 'customer_email')) {
                    $table->string('customer_email')->nullable();
                }
                if (!Schema::hasColumn('quotes', 'note')) {
                    $table->text('note')->nullable();
                }
                if (!Schema::hasColumn('quotes', 'base_price_snapshot')) {
                    $table->decimal('base_price_snapshot', 10, 2);
                }
                if (!Schema::hasColumn('quotes', 'color_price_snapshot')) {
                    $table->decimal('color_price_snapshot', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('quotes', 'options_price_snapshot')) {
                    $table->decimal('options_price_snapshot', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('quotes', 'total_price_snapshot')) {
                    $table->decimal('total_price_snapshot', 10, 2);
                }
                if (!Schema::hasColumn('quotes', 'status')) {
                    $table->string('status')->default('pending');
                }
            });
        }

        if (!Schema::hasTable('quote_items')) {
            Schema::create('quote_items', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('quote_id')->constrained('quotes')->cascadeOnDelete();
                $table->foreignId('option_id')->constrained('product_options')->cascadeOnDelete();
                $table->decimal('option_price_snapshot', 10, 2);
                $table->timestamps();
            });
        } else {
            Schema::table('quote_items', function (Blueprint $table): void {
                if (!Schema::hasColumn('quote_items', 'quote_id')) {
                    $table->foreignId('quote_id')->constrained('quotes')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('quote_items', 'option_id')) {
                    $table->foreignId('option_id')->constrained('product_options')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('quote_items', 'option_price_snapshot')) {
                    $table->decimal('option_price_snapshot', 10, 2);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
    }
};
