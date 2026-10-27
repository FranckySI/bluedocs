<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'user')
            ->update(['role' => 'editeur']);

        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('editeur')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('role', 'editeur')
            ->update(['role' => 'user']);

        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('user')->change();
        });
    }
};
