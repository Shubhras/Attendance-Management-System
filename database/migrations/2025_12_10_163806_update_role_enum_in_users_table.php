<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('admin','supervisor','operator','hr') 
            DEFAULT 'operator'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('admin','supervisor','operator') 
            DEFAULT 'operator'
        ");
    }
};
