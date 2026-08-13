<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    
     public function up()
  {
    Schema::create('evaluations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('team_id')->constrained()->onDelete('cascade');
        $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');

        $table->integer('marks')->nullable();
        $table->text('remarks')->nullable();

        $table->string('phase')->default('progress'); 
        // progress / final / proposal

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
        Schema::dropIfExists('evaluations');
    }
}
