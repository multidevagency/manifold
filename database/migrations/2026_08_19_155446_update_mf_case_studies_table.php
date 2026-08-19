<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mf_case_studies', function (Blueprint $table) {
            $table->string('client', 255)->nullable();
            $table->string('industry', 255)->nullable();
            $table->json('roles')->nullable();
            $table->json('gallery')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mf_case_studies', function (Blueprint $table) {
            $table->dropColumn('client');
            $table->dropColumn('industry');
            $table->dropColumn('roles');
            $table->dropColumn('gallery');
        });
    }
};
