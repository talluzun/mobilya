<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'category_id')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('products', 'category')) {
            $categories = DB::table('categories')->get(['id', 'slug']);

            foreach ($categories as $category) {
                DB::table('products')
                    ->whereNull('category_id')
                    ->where('category', $category->slug)
                    ->update(['category_id' => $category->id]);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'category_id')) {
            return;
        }

        Schema::table('products', function (Blueprint $table): void {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
