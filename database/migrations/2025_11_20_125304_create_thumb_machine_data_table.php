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
    Schema::create('thumb_machine_data', function (Blueprint $table) {
        $table->id(); // auto increment
        $table->json('thumb_template_data'); // json data from frontend
        $table->timestamps(); // created_at, updated_at
    });
}

public function down()
{
    Schema::dropIfExists('thumb_machine_data');
}

};
