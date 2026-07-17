<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_settings') && Schema::hasColumn('site_settings', 'draft')) {
            return;
        }

        if (Schema::hasTable('site_settings')) {
            Schema::drop('site_settings');
        }

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('appearance')->index();
            $table->unsignedInteger('version')->default(0);
            $table->json('draft')->nullable();
            $table->json('published')->nullable();
            $table->foreignId('draft_edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('draft_updated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['group', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
