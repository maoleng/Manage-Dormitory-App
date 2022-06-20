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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->date('birthday');
            $table->boolean('gender');
            $table->string('birthplace', 250);
            $table->string('ethnic', 250);
            $table->string('religion', 250)->nullable();
            $table->string('phone', 25);
            $table->string('identify_card', 250);
            $table->string('address', 250);
            $table->string('area', 250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information');
    }
};
