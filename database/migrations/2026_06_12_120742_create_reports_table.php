<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::create('reports', function (Blueprint $table) {
        $table->id();

        $table->string('title')->default('FYP System Report');

        $table->integer('total_teams')->default(0);
        $table->integer('total_students')->default(0);
        $table->integer('total_supervisors')->default(0);

        $table->integer('total_proposals')->default(0);
        $table->integer('approved_proposals')->default(0);
        $table->integer('pending_proposals')->default(0);
        $table->integer('rejected_proposals')->default(0);

        $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();

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
        Schema::dropIfExists('reports');
    }
}
