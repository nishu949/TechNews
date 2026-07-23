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
    Schema::create('articles', function (Blueprint $table) {
        $table->id();

        // Author of the article
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // Category of the article
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();

        // Article details
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('excerpt')->nullable();
        $table->longText('content');

        // Featured image
        $table->string('featured_image')->nullable();

        // Publication status
        $table->enum('status', ['draft', 'published'])->default('draft');

        // Publish date
        $table->timestamp('published_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
