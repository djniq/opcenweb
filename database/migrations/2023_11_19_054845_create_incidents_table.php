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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_facility_id');
            $table->foreign('health_facility_id')->references('id')->on('health_facilities');
            $table->dateTime('reported_datetime');
            $table->enum('nature_of_operation', ['conduction', 'emergency-dispatch-trauma', 'emergency-dispatch-medical', 'deployment'])->nullable(false);
            $table->enum('transfer_category', ['step-up', 'step-down'])->nullable(false);
            $table->enum('transfer_vicinity', ['within', 'outside'])->comment('within: Inside La Union; outside: Outside La Union')->nullable(false);
            $table->unsignedBigInteger('from_health_facility_id')->nullable();
            $table->foreign('from_health_facility_id')->references('id')->on('health_facilities');
            $table->unsignedBigInteger('to_health_facility_id')->nullable();
            $table->foreign('to_health_facility_id')->references('id')->on('health_facilities');
            $table->json('origin')->nullable(false);
            $table->json('destination')->nullable(false);
            $table->tinyInteger('status')->default(0)->comment('0: open; 1:dispatch initiated; 2: dispatch-completed; 3: closed without dispatch');
            $table->boolean('deleted')->default(false);
            $table->bigInteger('patient_ehr_id')->unsigned()->nullable()->comment('EHR patient ID. Null if not from EHR');
            $table->string('patient_first_name', 55)->nullable(false);
            $table->string('patient_last_name', 55)->nullable(false);
            $table->string('patient_middle_name', 55)->nullable();
            $table->date('patient_birthdate');
            $table->string('patient_address')->comment('Manually inputted address');
            $table->string('chief_complaint')->nullable(false);
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->unsignedBigInteger('created_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('incidents');
    }
};
