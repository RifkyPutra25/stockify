<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    DB::statement("ALTER TABLE stock_transactions MODIFY COLUMN status ENUM('pending', 'confirmed', 'rejected') DEFAULT 'pending'");

    Schema::table('stock_transactions', function (Blueprint $table) {
        $table->text('rejection_reason')->nullable()->after('notes');
    });
}

public function down(): void
{
    Schema::table('stock_transactions', function (Blueprint $table) {
        $table->dropColumn('rejection_reason');
    });

    DB::statement("ALTER TABLE stock_transactions MODIFY COLUMN status ENUM('pending', 'confirmed') DEFAULT 'pending'");
}
};
