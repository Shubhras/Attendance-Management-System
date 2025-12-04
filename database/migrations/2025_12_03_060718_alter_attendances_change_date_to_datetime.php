<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dateTime('date')->change();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->date('date')->change();
        });
    }
};
