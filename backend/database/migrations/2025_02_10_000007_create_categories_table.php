<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (DB::table('categories')->count() === 0 && Schema::hasTable('products')) {
            $categories = DB::table('products')
                ->select('category')
                ->whereNotNull('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');

            foreach ($categories as $category) {
                $slug = Str::slug($category);
                $name = Str::title(str_replace('_', ' ', $category));

                if ($slug === '') {
                    continue;
                }

                DB::table('categories')->updateOrInsert(
                    ['slug' => $slug],
                    ['name' => $name, 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
