<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('proposals', function (Blueprint $table) {

            // only add if not exists (safe check)
            if (!Schema::hasColumn('proposals', 'team_id')) {
                $table->foreignId('team_id')
                    ->nullable()
                    ->constrained('teams')
                    ->onDelete('cascade');
            }

            // ❌ REMOVE status from here (already exists in your DB)
        });
    }

    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            if (Schema::hasColumn('proposals', 'team_id')) {
                $table->dropForeign(['team_id']);
                $table->dropColumn('team_id');
            }
        });
    }
};