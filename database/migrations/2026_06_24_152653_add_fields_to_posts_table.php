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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('slug')->unique()->after('title');
            $table->text('excerpt')->nullable()->after('slug');
            $table->longText('content')->nullable()->after('excerpt');
            $table->string('image')->nullable()->after('content');
            $table->enum('status', ['published', 'draft'])->default('draft')->after('image');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['title', 'slug', 'excerpt', 'content', 'image', 'status', 'category_id']);
        });
    }
};
