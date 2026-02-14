<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('yandex_setting_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('external_id')->unique();
            $table->string('author_name')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('text')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};