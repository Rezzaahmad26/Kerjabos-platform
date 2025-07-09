<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('thumbnail');
            $table->string('skill_level');
            $table->text('about');
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); //artinya category_id adalah foreign key yang mengacu pada id di tabel categories
            $table->unsignedBigInteger('client_id'); // artinya angka id selalu lebih dari 0
            $table->unsignedBigInteger('budget'); // artinya angka selalu lebih dari 0
            $table->boolean('has_finished');
            $table->boolean('has_started');
            $table->softDeletes();
            $table->timestamps();


                //refference client_id ke users table
                $table->foreign('client_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade'); // artinya jika user dihapus maka project juga akan dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
