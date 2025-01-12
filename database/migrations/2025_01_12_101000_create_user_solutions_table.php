<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSolutionsTable extends Migration
{
    public function up()
    {
        Schema::create('user_solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_material_deadline_id');
            $table->unsignedBigInteger('user_id');
            $table->string('solution_file');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('task_material_deadline_id')->references('id')->on('task_material_deadlines')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_solutions');
    }
}
