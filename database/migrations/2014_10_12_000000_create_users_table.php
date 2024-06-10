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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_facility_id');
            $table->string('first_name', 55)->nullable(false);
            $table->string('last_name', 55)->nullable(false);
            $table->string('middle_name', 55)->nullable();
            $table->string('email', 100)->unique()->nullable(false);
            $table->string('username', 100)->unique()->nullable(false);
            $table->string('password');
            $table->bigInteger('contact_no')->nullable(false)->unsigned()->comment('Mobile number that will receive the text notification.');
            $table->tinyInteger('status')->nullable(false)->comment('1: active; 2: inactive; 3: soft deleted');
            $table->enum('role', ['superadmin', 'hfadmin', 'opcen', 'emt'])->comment('superAdmin: All access super user; hfadmin: Health Facility administrator; opcen: Primary Opcen app user; emt: EMT staff/responder group account;');
            $table->unsignedBigInteger('updated_by')->nullable()->default(null);
            $table->unsignedBigInteger('created_by');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
