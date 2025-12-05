<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->default(0)
                ->comment('0 = leave, 1 = present, 2 = half_day')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->change();
        });
    }
};

