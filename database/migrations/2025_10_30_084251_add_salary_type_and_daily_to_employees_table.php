<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('salary_type', ['monthly', 'daily'])->default('monthly')->after('machine');
            $table->decimal('salary_daily', 10, 2)->nullable()->after('salary_monthly');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['salary_type', 'salary_daily']);
        });
    }
};
