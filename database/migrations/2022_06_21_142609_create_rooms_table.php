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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('type', 250);
            $table->integer('amount');
            $table->string('status', 250)->nullable();
            $table->foreignId('lead_id')->nullable()->constrained('students');
//            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreignId('floor_id')->constrained('floors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
