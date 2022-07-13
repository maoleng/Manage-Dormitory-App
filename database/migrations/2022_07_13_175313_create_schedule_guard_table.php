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
        Schema::create('schedule_guard', function (Blueprint $table) {
            $table->foreignid('schedule_id')->constrained('schedules');
            $table->foreignId('student_id')->constrained('students');
            $table->primary(['schedule_id', 'student_id']);
            $table->boolean('is_check_in')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_guard');
    }
};
