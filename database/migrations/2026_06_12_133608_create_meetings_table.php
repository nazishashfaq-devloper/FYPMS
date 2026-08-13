<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('meetings', function (Blueprint $table) {
        $table->id();

        $table->foreignId('team_id')->constrained()->onDelete('cascade');

        $table->foreignId('supervisor_id')
              ->constrained('users')
              ->onDelete('cascade');

        $table->date('meeting_date');
        $table->time('meeting_time');

        $table->string('venue')->nullable();
        $table->string('meeting_link')->nullable();

        $table->text('agenda')->nullable();

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
