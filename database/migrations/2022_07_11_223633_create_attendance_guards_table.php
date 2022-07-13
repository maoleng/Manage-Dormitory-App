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
        Schema::create('attendance_guards', function (Blueprint $table) {
            $table->foreignId('attendance_id')->constrained('attendances');
            $table->foreignId('student_id')->constrained('students');
            $table->integer('period');
            $table->boolean('is_check_in')->default(true);
            $table->string('note', 250)->nullable();
            $table->primary(['attendance_id', 'student_id', 'period']);
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
        Schema::dropIfExists('attendance_guards');
    }
};
