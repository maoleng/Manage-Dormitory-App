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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('type', 250);
            $table->text('content');
            $table->boolean('is_finish');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('answer_id')->nullable()->constrained('forms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
};
