<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->string('category')->nullable(false);
            $table->string('purpose_of_visit')->nullable(false);
            $table->string('case_type')->nullable(false);
            $table->date('DOA')->nullable();

            $table->id();
            $table->unsignedBigInteger('insurance_id')->nullable();
            $table->unsignedBigInteger('firm_id')->nullable();
            $table->unsignedBigInteger('practice_location_id')->nullable();
            $table->unsignedBigInteger('PID');
            $table->timestamps();
            $table->softDeletes();

            //Foreign Keys
            $table->foreign('PID')->references('patient_id')->on('patients')->onDelete('cascade');
            $table->foreign('insurance_id')->references('id')->on('insurances')->onDelete('set null');
            $table->foreign('firm_id')->references('id')->on('firms')->onDelete('set null');
            $table->foreign('practice_location_id')->references('id')->on('practice_locations')->onDelete('set null');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
