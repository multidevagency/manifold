<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mf_pages', function (Blueprint $table) {
            $table->json('hero')->nullable();
            $table->json('layout')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('faq')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mf_pages', function (Blueprint $table) {
            $table->dropColumn('hero');
            $table->dropColumn('layout');
            $table->dropColumn('meta_description');
            $table->dropColumn('faq');
        });
    }
};
