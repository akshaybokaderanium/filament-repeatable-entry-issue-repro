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
        Schema::create('consentables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('consent_option_id');
            $table->unsignedBigInteger('consentable_id');
            $table->string('consentable_type');
            $table->string('key')->default(NULL)->nullable();
            $table->boolean('accepted')->default(0);
            $table->json('fields')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consentables');
    }
};
