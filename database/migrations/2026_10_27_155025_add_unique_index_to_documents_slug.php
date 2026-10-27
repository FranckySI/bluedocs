<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasIndex('documents', ['slug'], 'unique')) {
            Schema::table('documents', function (Blueprint $table): void {
                $table->unique('slug', 'documents_slug_unique_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasIndex('documents', 'documents_slug_unique_index')) {
            Schema::table('documents', function (Blueprint $table): void {
                $table->dropUnique('documents_slug_unique_index');
            });
        }
    }
};
