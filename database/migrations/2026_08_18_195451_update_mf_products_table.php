<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mf_products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable();
            $table->string('status', 64)->default('draft')->index();
            $table->boolean('in_stock')->default(true);
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->json('variants')->nullable();
            $table->string('meta_title', 70)->nullable();
            $table->text('meta_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mf_products', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('status');
            $table->dropColumn('in_stock');
            $table->dropColumn('category_id');
            $table->dropColumn('variants');
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
        });
    }
};
