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
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slogan')->nullable();
            $table->string('address')->nullable();

            $table->string('eiin', 50)->nullable();
            $table->string('school_code', 50)->nullable();
            $table->string('college_code', 50)->nullable();

            $table->string('phone_1', 50)->nullable();
            $table->string('phone_2', 50)->nullable();
            $table->string('mobile_1', 50)->nullable();
            $table->string('mobile_2', 50)->nullable();

            $table->text('link_1')->nullable();
            $table->text('link_2')->nullable();
            $table->text('link_3')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
};
