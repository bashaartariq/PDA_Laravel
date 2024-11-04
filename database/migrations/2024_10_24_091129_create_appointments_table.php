<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('case_id')->nullable(false);
            $table->unsignedBigInteger('speciality_id');
            $table->unsignedBigInteger('practice_location_id');
            $table->date('date')->nullable();
            $table->time('appointment_time');
            $table->integer('Duration');
            $table->string('Description');
            $table->foreign('speciality_id')->references('id')->on('specialities');
            $table->foreign('practice_location_id')->references('id')->on('practice_locations');
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
