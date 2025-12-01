<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');
        $table->date('date');
        $table->time('clock_in')->nullable();
        $table->time('clock_out')->nullable();
        $table->enum('status', ['present','absent','pending','leave'])->default('pending');
        $table->json('fingerprint_template')->nullable(); // stored template or enroll data
        $table->json('scan_response')->nullable(); // last scan response JSON
        $table->unsignedBigInteger('marked_by')->nullable();
        $table->timestamps();

        $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
        $table->foreign('marked_by')->references('id')->on('users')->nullOnDelete();
        $table->unique(['employee_id','date']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
