<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('mobile_2');
            $table->string('header_banner_path')->nullable()->after('logo_path');
            $table->string('online_apply_url')->nullable()->after('header_banner_path');
        });
    }

    public function down(): void
    {
        Schema::table('institutes', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'header_banner_path',
                'online_apply_url',
            ]);
        });
    }
};
