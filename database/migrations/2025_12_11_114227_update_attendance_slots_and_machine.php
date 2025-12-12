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
    Schema::table('attendances', function (Blueprint $table) {

        // machine_id sent from frontend
        $table->unsignedBigInteger('machine_id')->nullable()->after('employee_id');

        // 3 slot timestamps
        $table->timestamp('slot1')->nullable()->after('date');
        $table->timestamp('slot2')->nullable()->after('slot1');
        $table->timestamp('slot3')->nullable()->after('slot2');

        // scan_status already exists - OK
    });
}

public function down()
{
    Schema::table('attendances', function (Blueprint $table) {
        $table->dropColumn(['machine_id','slot1','slot2','slot3']);
    });
}

};
