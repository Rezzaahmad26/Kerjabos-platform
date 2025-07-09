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
        Schema::create('project_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); //artinya project_id adalah foreign key yang mengacu pada id di tabel projects
            $table->unsignedBigInteger('freelancer_id'); // artinya angka id selalu lebih dari 0
            $table->text('message');
            $table->string('status'); // status = pending, accepted, rejected
            $table->softDeletes();
            $table->timestamps();

            //refference freelancer_id ke users table
            $table->foreign('freelancer_id')
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
        Schema::dropIfExists('project_applicants');
    }
};
