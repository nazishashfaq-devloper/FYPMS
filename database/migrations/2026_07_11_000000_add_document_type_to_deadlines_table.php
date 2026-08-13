<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentTypeToDeadlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * A NULL document_type means the deadline is a general deadline that
     * applies to every document type that does not have its own
     * type-specific deadline. This keeps all existing deadline rows
     * (created before this column existed) working exactly as before.
     */
    public function up()
    {
        Schema::table('deadlines', function (Blueprint $table) {
            $table->string('document_type')->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('deadlines', function (Blueprint $table) {
            $table->dropColumn('document_type');
        });
    }
}
