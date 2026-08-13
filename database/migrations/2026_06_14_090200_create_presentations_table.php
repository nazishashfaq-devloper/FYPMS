<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presentations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('scheduled_by')->nullable()->constrained('users')->onDelete('set null');

            // proposal_defense / progress_evaluation / final_defense
            $table->string('phase')->default('final_defense');

            $table->date('presentation_date');
            $table->time('presentation_time');

            $table->string('venue')->nullable();
            $table->string('meeting_link')->nullable();
            $table->text('panel_members')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentations');
    }
};
