<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table): void {
            if (! Schema::hasColumn('quotes', 'ref_code')) {
                $table->string('ref_code')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('quotes', 'customer_first_name')) {
                $table->string('customer_first_name')->nullable()->after('selected_color_id');
            }
            if (! Schema::hasColumn('quotes', 'customer_last_name')) {
                $table->string('customer_last_name')->nullable()->after('customer_first_name');
            }
            if (! Schema::hasColumn('quotes', 'company_name')) {
                $table->string('company_name')->nullable()->after('customer_email');
            }
            if (! Schema::hasColumn('quotes', 'selected_color_value_id')) {
                $table->foreignId('selected_color_value_id')->nullable()->after('selected_color_id')->constrained('product_option_values')->nullOnDelete();
            }
            if (! Schema::hasColumn('quotes', 'selected_color_label')) {
                $table->string('selected_color_label')->nullable()->after('selected_color_value_id');
            }
            if (! Schema::hasColumn('quotes', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('note');
            }
            if (! Schema::hasColumn('quotes', 'internal_note')) {
                $table->text('internal_note')->nullable()->after('status');
            }
        });

        Schema::table('quote_items', function (Blueprint $table): void {
            if (! Schema::hasColumn('quote_items', 'option_value_id')) {
                $table->foreignId('option_value_id')->nullable()->after('option_id')->constrained('product_option_values')->nullOnDelete();
            }
            if (! Schema::hasColumn('quote_items', 'option_label_snapshot')) {
                $table->string('option_label_snapshot')->nullable()->after('option_value_id');
            }
            if (! Schema::hasColumn('quote_items', 'value_label_snapshot')) {
                $table->string('value_label_snapshot')->nullable()->after('option_label_snapshot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quote_items', function (Blueprint $table): void {
            if (Schema::hasColumn('quote_items', 'option_value_id')) {
                $table->dropConstrainedForeignId('option_value_id');
            }
            if (Schema::hasColumn('quote_items', 'option_label_snapshot')) {
                $table->dropColumn('option_label_snapshot');
            }
            if (Schema::hasColumn('quote_items', 'value_label_snapshot')) {
                $table->dropColumn('value_label_snapshot');
            }
        });

        Schema::table('quotes', function (Blueprint $table): void {
            if (Schema::hasColumn('quotes', 'selected_color_value_id')) {
                $table->dropConstrainedForeignId('selected_color_value_id');
            }

            foreach (['ref_code', 'customer_first_name', 'customer_last_name', 'company_name', 'selected_color_label', 'quantity', 'internal_note'] as $column) {
                if (Schema::hasColumn('quotes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
