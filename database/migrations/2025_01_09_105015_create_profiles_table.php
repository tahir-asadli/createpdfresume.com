<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 64)->nullable();
            $table->string('job_title', 64)->nullable();
            $table->string('slogan', 128)->nullable();
            $table->string('about', 512)->nullable();
            $table->date('birthday')->nullable();
            $table->string('profile_image', 64)->nullable();
            $table->string('phone', 64)->nullable();
            $table->string('email', 64)->nullable();
            $table->string('web', 64)->nullable();
            $table->string('address', 128)->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
