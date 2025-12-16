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
        Schema::create('statements', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable(); 
            $table->longText('body');

            $table->string('author_name')->nullable();
            $table->string('author_designation')->nullable();
            $table->text('author_photo_path')->nullable();

            $table->integer('position')->default(0)->index();
            $table->boolean('status')->default(true)->index();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statements');
    }
};
