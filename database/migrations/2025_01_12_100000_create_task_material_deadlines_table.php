<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Запуск миграций.
     */
    public function up(): void
    {
        Schema::create('task_material_deadlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_link_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('deadline')->nullable();
            $table->timestamps();

            $table->foreign('material_link_id')->references('id')->on('material_links')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['material_link_id', 'user_id']);
        });
    }

    /**
     * Откат миграций.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_material_deadlines');
    }
};
