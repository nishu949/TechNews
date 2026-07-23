<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Add parent_id for nested comments
            $table->foreignId('parent_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('comments')
                  ->onDelete('cascade');
            
            // Add status for comment moderation
            $table->enum('status', ['pending', 'approved', 'spam'])
                  ->default('pending')
                  ->after('comment');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'status']);
        });
    }
};