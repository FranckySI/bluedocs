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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });

        DB::table('settings')->insert([
            ['key' => 'documentation_font_family', 'value' => 'sans', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'documentation_font_size', 'value' => '16', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'documentation_text_color', 'value' => '#0a1420', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'documentation_heading_color', 'value' => '#003366', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'documentation_background_color', 'value' => '#ffffff', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'documentation_line_height', 'value' => '1.6', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
