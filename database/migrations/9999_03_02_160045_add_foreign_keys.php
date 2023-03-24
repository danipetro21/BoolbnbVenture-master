<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->constrained('users');
        });

        Schema::table('messages', function (Blueprint $table) {

            $table->foreignId('property_id')
            ->constrained('properties')
            ->onDelete('cascade');
        });

        
        Schema::table('views', function (Blueprint $table) {

            $table->foreignId('property_id')
            ->constrained('properties')
            ->onDelete('cascade');
        });


        Schema::table('property_service', function (Blueprint $table) {

            $table->foreignId('property_id')
            ->constrained('properties');
            $table->foreignId('service_id')
                ->constrained('services')
                ->onDelete('cascade');
        });
        Schema::table('property_sponsorship', function (Blueprint $table) {

            $table->foreignId('property_id')
            ->constrained('properties');
            $table->foreignId('sponsorship_id')
                ->constrained('sponsorships')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign('properties_user_id_foreign');
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_property_id_foreign');
        });
        Schema::table('views', function (Blueprint $table) {
            $table->dropForeign('views_property_id_foreign');
        });

        Schema::table('property_service', function (Blueprint $table) {

            $table->dropForeign('property_service_property_id_foreign');
            $table->dropForeign('property_service_service_id_foreign');
        });

        Schema::table('property_sponsorship', function (Blueprint $table) {

            $table->dropForeign('property_sponsorship_property_id_foreign');
            $table->dropForeign('property_sponsorship_sponsorship_id_foreign');
        });
    }
};