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
    Schema::table('advance_payments', function (Blueprint $table) {
        $table->boolean('is_settled')->default(false)->after('amount');
        $table->foreignId('settled_salary_id')
            ->nullable()
            ->constrained('salary_payments')
            ->nullOnDelete();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_payments', function (Blueprint $table) {
            //
        });
    }
};
