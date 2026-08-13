<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('proposals', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');

        $table->string('title');
        $table->string('domain');
        $table->text('description');

        $table->string('status')->default('pending'); 
        $table->text('feedback')->nullable();

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
        Schema::dropIfExists('proposals');
    }
}
