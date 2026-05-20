<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'ref_code')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->string('ref_code')->nullable()->unique();
            });
        }

        $products = DB::table('products')
            ->orderBy('id')
            ->select('id')
            ->get();

        $counter = 1;

        foreach ($products as $product) {
            $refCode = sprintf('MS%06d', $counter);

            DB::table('products')
                ->where('id', $product->id)
                ->update(['ref_code' => $refCode]);

            $counter++;
        }

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY ref_code VARCHAR(255) NOT NULL');
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE products ALTER COLUMN ref_code SET NOT NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'ref_code')) {
            return;
        }

        Schema::table('products', function (Blueprint $table): void {
            $table->dropUnique(['ref_code']);
            $table->dropColumn('ref_code');
        });
    }
};
