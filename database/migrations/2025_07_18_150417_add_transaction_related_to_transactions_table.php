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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('transaction_related_id')
                ->nullable()
                ->constrained('transactions')
                ->onDelete('set null')
                ->comment('ID de la transacción relacionada, si aplica');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_related_id']);
            $table->dropColumn('transaction_related_id');
        });
    }
};
