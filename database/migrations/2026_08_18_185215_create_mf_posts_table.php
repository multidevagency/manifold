<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mf_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug')->nullable()->unique();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('status', 64)->default('draft')->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->boolean('featured')->default(false);
            $table->dateTime('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mf_posts');
    }
};
