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
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->string('gid')->nullable();
            $table->string('uid')->nullable();
            $table->string('uuid')->nullable();
            $table->string('country')->nullable();
            $table->string('ip')->nullable();
            $table->string('method')->nullable();
            $table->string('uri')->nullable();
            $table->string('ref')->nullable();
            $table->boolean('bot')->default(false);
            $table->text('agent')->nullable();
            $table->integer('duration')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
