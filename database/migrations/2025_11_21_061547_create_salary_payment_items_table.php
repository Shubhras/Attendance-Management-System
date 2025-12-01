<?php

// database/migrations/2025_11_21_000002_create_salary_payment_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryPaymentItemsTable extends Migration
{
    public function up()
    {
        Schema::create('salary_payment_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('salary_payment_id');
            $table->string('title'); // e.g., "Base Salary", "Present days pay", "Half day deduction"
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('salary_payment_id')->references('id')->on('salary_payments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_payment_items');
    }
}

