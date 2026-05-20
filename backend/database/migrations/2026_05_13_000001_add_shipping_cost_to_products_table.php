<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'shipping_cost')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->decimal('shipping_cost', 10, 2)->nullable()->after('base_price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'shipping_cost')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('shipping_cost');
            });
        }
    }
};
