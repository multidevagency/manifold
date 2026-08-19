<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mf_case_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug')->nullable()->unique();
            $table->string('tagline', 255)->nullable();
            $table->string('category', 64)->default('product');
            $table->string('stack', 255)->nullable();
            $table->bigInteger('year')->nullable()->default(2026);
            $table->boolean('featured')->default(false);
            $table->bigInteger('sort_order')->nullable()->default(99);
            $table->text('summary');
            $table->longText('body')->nullable();
            $table->json('metrics')->nullable();
            $table->string('hero')->nullable();
            $table->string('repo_url', 255)->nullable();
            $table->string('live_url', 255)->nullable();
            $table->string('status', 64)->default('published');
            $table->string('meta_title', 70)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mf_case_studies');
    }
};
