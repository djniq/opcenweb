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
        Schema::create('health_facilities', function (Blueprint $table) {
            $table->id();
            $table->string('hf_name')->nullable(false);
            $table->json('hf_location')->nullable(false)->comment('JSON formatted address from google maps');
            $table->string('hf_email')->nullable(false);
            $table->string('hf_contact_no')->nullable(false);
            $table->enum('hf_level', ['primary', 'secondary', 'tertiary', 'specialized'])->nullable(false);
            $table->tinyInteger('status')->nullable(false)->comment('1: active; 2: inactive; 3: soft deleted');
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
        Schema::dropIfExists('health_facilities');
    }
};
