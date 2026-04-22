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
        Schema::create('manga_user', function (Blueprint $table) {
            $table->foreignId('manga_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('plan_to_read');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->unsignedInteger('current_chapter')->default(0);
            $table->timestamps();

            $table->primary(['manga_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manga_user');
    }
};
