<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'scan_status')) {
                $table->tinyInteger('scan_status')->default(0)->after('status');
            }

            if (!Schema::hasColumn('attendances', 'clock_in')) {
                $table->time('clock_in')->nullable()->after('scan_status');
            }

            if (!Schema::hasColumn('attendances', 'clock_out')) {
                $table->time('clock_out')->nullable()->after('clock_in');
            }

            if (!Schema::hasColumn('attendances', 'marked_by')) {
                $table->bigInteger('marked_by')->nullable()->after('clock_out');
            }
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['scan_status', 'clock_in', 'clock_out', 'marked_by']);
        });
    }
};
