<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('document_section', function (Blueprint $table) {
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->primary(['section_id', 'document_id']);
        });

        DB::table('documents')
            ->where('title', '<>', 'Sommaire')
            ->select(['id', 'title'])
            ->orderBy('id')
            ->chunkById(100, function (Collection $documents): void {
                foreach ($documents as $document) {
                    $sectionId = DB::table('sections')->insertGetId([
                        'title' => $document->title,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('document_section')->insert([
                        'section_id' => $sectionId,
                        'document_id' => $document->id,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_section');
        Schema::dropIfExists('sections');
    }
};
