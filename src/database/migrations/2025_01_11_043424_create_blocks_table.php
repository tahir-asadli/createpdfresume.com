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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->integer('resume_page')->default(1);
            $table->string('title', 1024)->nullable();
            $table->integer('from')->nullable();
            $table->integer('to')->nullable();
            $table->boolean('active')->default(false);
            $table->integer('height')->default(10);
            $table->boolean('bold')->default(false);
            $table->boolean('italic')->default(false);
            $table->boolean('underline')->default(false);
            $table->boolean('strikethrough')->default(false);
            $table->integer('radius')->nullable();
            $table->string('filter', 64)->nullable();
            $table->string('color', 64)->nullable();
            $table->string('align')->nullable();
            $table->boolean('uppercase')->default(false);
            $table->string('section', 64);
            $table->unsignedInteger('order');
            $table->foreignId('widget_id')->constrained()->onDelete('cascade');
            $table->foreignId('resume_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
