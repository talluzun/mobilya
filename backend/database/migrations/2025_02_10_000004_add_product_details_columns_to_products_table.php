<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->text('features')->nullable()->after('description');
            $table->text('care_instructions')->nullable()->after('features');
            $table->text('warranty_info')->nullable()->after('care_instructions');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['features', 'care_instructions', 'warranty_info']);
        });
    }
};
