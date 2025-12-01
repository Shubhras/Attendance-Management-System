<?php

// database/migrations/2025_11_21_000001_create_salary_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('month')->nullable(); // e.g. '2025-11' for monthly reports
            $table->date('date_paid')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->decimal('gross_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_payments');
    }
}

