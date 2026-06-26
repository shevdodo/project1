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
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete()->after('id');
            }
            if (!Schema::hasColumn('pages', 'template')) {
                $table->string('template')->default('default')->after('status');
            }
            if (!Schema::hasColumn('pages', 'order')) {
                $table->integer('order')->default(0)->after('template');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'template', 'order']);
        });
    }
};
