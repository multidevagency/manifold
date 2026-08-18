<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mf_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug')->nullable()->unique();
            $table->longText('body')->nullable();
            $table->string('status', 64)->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mf_pages');
    }
};
