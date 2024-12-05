<?php

use App\Models\File;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('extension', 10);
            $table->unsignedBigInteger('fileable_id');
            $table->string('fileable_type');
            $table->string('mime_type');
            $table->integer('pages')->nullable();
            $table->string('path');
            $table->unsignedBigInteger('size');
            $table->enum('visibility', ['public', 'private'])->default('private');
            $table->timestamps();

            $table->index(['fileable_type', 'fileable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (File::all() as $file) {
            if ($file->visibility === 'public') {
                Storage::disk('public')->delete($file->path);
            } else {
                Storage::disk('private')->delete($file->path);
            }
        }

        Schema::dropIfExists('files');
    }
};
