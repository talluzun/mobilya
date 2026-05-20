<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('product_option_values', 'hex_color') && ! Schema::hasColumn('product_option_values', 'color_hex')) {
            Schema::table('product_option_values', function (Blueprint $table): void {
                $table->renameColumn('hex_color', 'color_hex');
            });
        }

        if (! Schema::hasColumn('product_option_values', 'hex_color') && ! Schema::hasColumn('product_option_values', 'color_hex')) {
            Schema::table('product_option_values', function (Blueprint $table): void {
                $table->string('color_hex', 7)->nullable()->after('label');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product_option_values', 'color_hex') && ! Schema::hasColumn('product_option_values', 'hex_color')) {
            Schema::table('product_option_values', function (Blueprint $table): void {
                $table->renameColumn('color_hex', 'hex_color');
            });
        }
    }
};
