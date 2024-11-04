<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->string('home_phone')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('ssn')->nullable();
            $table->string('address')->notNullable();
            $table->string('city');
            $table->string('zip');
            $table->string('state');
            $table->timestamps();
            $table->unsignedBigInteger('patient_id')->primary();
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('patient');
    }
}
