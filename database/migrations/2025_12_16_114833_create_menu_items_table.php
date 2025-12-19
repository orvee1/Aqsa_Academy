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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('menu_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();

            $table->string('label_bn');
            $table->string('label_en')->nullable();

            $table->enum('type', ['url', 'page', 'post_category', 'route'])->default('url');
            $table->text('url')->nullable();
            $table->unsignedBigInteger('page_id')->nullable()->index();
            $table->unsignedBigInteger('post_category_id')->nullable()->index();
            $table->string('route_name')->nullable();

            $table->integer('position')->default(0)->index();
            $table->boolean('open_new_tab')->default(false);
            $table->boolean('status')->default(true)->index();

            $table->timestamps();
            $table->index(['menu_id', 'parent_id', 'position']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
