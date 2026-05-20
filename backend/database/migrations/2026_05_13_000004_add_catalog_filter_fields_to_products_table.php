<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            if (! Schema::hasColumn('products', 'room_type')) {
                $table->string('room_type')->nullable()->after('category');
            }
            if (! Schema::hasColumn('products', 'material')) {
                $table->string('material')->nullable()->after('room_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['room_type', 'material']);
        });
    }
};
