<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mf_pages', function (Blueprint $table) {
            $table->renameColumn('body', 'content');
            $table->string('meta_title', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mf_pages', function (Blueprint $table) {
            $table->renameColumn('content', 'body');
            $table->dropColumn('meta_title');
        });
    }
};
