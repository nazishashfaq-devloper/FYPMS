<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::create('documents', function (Blueprint $table) {
        $table->id();

        $table->foreignId('team_id')->constrained()->onDelete('cascade');
        $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');

        $table->string('document_type'); // proposal, srs, design, etc.
        $table->string('file_path');

        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
        Schema::dropIfExists('documents');
    }
}
