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
        Schema::create('electricity_waters', function (Blueprint $table) {
            $table->id();
            $table->double('electricity_count');
            $table->double('water_count');
            $table->double('money_per_kwh')->default('2500');
            $table->double('money_per_m3')->default('15000');
            $table->foreignId('subscription_id')->constrained('subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('electricity_waters');
    }
};
