<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Repairs history: a partially-failed ALTER left these columns present in
// some databases but absent from the migration files.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mf_products', function (Blueprint $table) {
            if (! Schema::hasColumn('mf_products', 'excerpt')) {
                $table->text('excerpt')->nullable();
            }
            if (! Schema::hasColumn('mf_products', 'description')) {
                $table->longText('description')->nullable();
            }
            if (! Schema::hasColumn('mf_products', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('mf_products', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'description', 'image']);
        });
    }
};
