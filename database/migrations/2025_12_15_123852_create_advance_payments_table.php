<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->foreignId('contractor_id')
                ->nullable()
                ->constrained('contractors')
                ->nullOnDelete();

            $table->foreignId('machine_id')
                ->nullable()
                ->constrained('machines')
                ->nullOnDelete();

            // NEW FIELD
            $table->foreignId('hr_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('employee_type', ['company','contractor']);

            $table->decimal('amount', 10, 2);
            $table->string('reason')->nullable();

            $table->boolean('thumb_verified')->default(false);

            $table->timestamp('paid_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advance_payments');
    }
};
