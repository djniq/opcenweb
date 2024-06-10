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
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id');
            $table->foreign('incident_id')->references('id')->on('incidents');
            $table->unsignedBigInteger('ambulance_id');
            $table->foreign('ambulance_id')->references('id')->on('ambulances');
            $table->unsignedBigInteger('driver_id');
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->unsignedBigInteger('crew_settings_id')->nullable();
            $table->foreign('crew_settings_id')->references('id')->on('crew_settings');
            $table->dateTime('tagged_ambulance_datetime')->comment('Time when the ambulance is tagged for dispatch')->nullable();
            $table->dateTime('start_datetime')->comment('Time when the dispatch status is changed to `start`')->nullable();
            $table->dateTime('arrived_on_site_datetime')->comment('Time when the dispatch status is changed to `arrived-on-site`')->nullable();
            $table->dateTime('moved_out_from_site_datetime')->comment('Time when the dispatch status is changed to `moved-out-from-site`')->nullable();
            $table->dateTime('arrived_to_destination_datetime')->comment('Time when the dispatch status is changed to `arrived-to-destination`')->nullable();
            $table->dateTime('endorsed_patient_datetime')->comment('Time when the dispatch status is changed to `endorsed-patient`')->nullable();
            $table->dateTime('completed_datetime')->comment('Time when the dispatch is completed`')->nullable();
            $table->json('last_recorded_location')->nullable();
            $table->string('remarks')->nullable();
            $table->enum('status', ['pending', 'start', 'arrived-on-site', 'moved-out-from-site', 'arrived-at-destination', 'endorsed-patient', 'completed'])->default('pending')->comment(' start, arrived-to-pick-up,  moved-out: move out from site, arrived to facility, endorse patient, completed: back to HQ/End');
            $table->boolean('deleted')->default(false);
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
        Schema::dropIfExists('dispatches');
    }
};
