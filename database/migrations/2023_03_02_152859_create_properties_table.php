<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('properties', function (Blueprint $table) {
        $table->id();
        $table->string('title', 128);
        $table->text('description');
        $table->tinyInteger('room_number');
        $table->tinyInteger('bath_number');
        $table->smallInteger('mq2');
        $table->string('address');
        $table->decimal('lat', 9, 6);
        $table->decimal('lon', 10, 6);
        $table->string('img')->default('home.jpg');
        $table->boolean('visible');
        $table->decimal('price_per_night', 3,0);
        $table->tinyInteger('bed_number');
        $table->timestamps();
    });

}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
};