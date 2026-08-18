<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mf_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('mf_posts', function (Blueprint $table) {
            $table->dropColumn('author_id');
        });
    }
};
