<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->integer('code_start')->nullable()->after('company_name');
            $table->integer('code_end')->nullable()->after('code_start');
        });
    }

    public function down()
    {
        Schema::table('contractors', function (Blueprint $table) {
            $table->dropColumn(['code_start', 'code_end']);
        });
    }
};

