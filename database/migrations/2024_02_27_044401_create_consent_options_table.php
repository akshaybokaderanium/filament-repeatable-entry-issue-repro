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
        Schema::create('consent_options', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->unsignedBigInteger('version')->nullable()->default(1);
            $table->string('title')->nullable()->default(null);
            $table->string('label')->nullable()->default(null);
            $table->text('text')->nullable()->default(null);
            $table->boolean('additional_info')->default(0);
            $table->string('additional_info_title')->nullable();
            $table->json('fields')->nullable();
            $table->boolean('is_mandatory')->default(0);
            $table->boolean('is_current')->default(0);
            $table->boolean('enabled')->default(0);
            $table->boolean('force_user_update')->default(0);
            $table->integer('sort_order')->default(0);
            $table->json('models')->nullable()->default(null);
            $table->dateTime('published_at')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consent_options');
    }
};
