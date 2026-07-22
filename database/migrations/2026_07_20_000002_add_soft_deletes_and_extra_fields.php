<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add soft deletes + new fields to products
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('sku');
            $table->string('unit', 50)->nullable()->default('pcs')->after('low_stock_threshold');
            $table->string('image_url')->nullable()->after('unit');
            $table->softDeletes();
        });

        // Add soft deletes to categories
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes + extra contacts to suppliers
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('website')->nullable()->after('address');
            $table->string('contact_name')->nullable()->after('website');
            $table->text('notes')->nullable()->after('contact_name');
            $table->softDeletes();
        });

        // Add correction type to transactions + reference_id for reversals
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('reference_id')->nullable()->after('user_id')
                ->comment('For reversals: points to the original transaction ID');
            // Extend type enum to include 'correction'
            // SQLite doesn't support ALTER COLUMN for ENUMs, so we use a string field approach
            $table->string('transaction_type', 20)->nullable()->after('type')
                ->comment('in, out, correction');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'unit', 'image_url']);
            $table->dropSoftDeletes();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['website', 'contact_name', 'notes']);
            $table->dropSoftDeletes();
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['reference_id', 'transaction_type']);
        });
    }
};
