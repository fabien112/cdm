<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained()->nullable()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->nullable()->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('code')->nullable();
            $table->string('mode')->nullable();
            $table->integer('mois')->nullable();
            $table->string('motif')->nullable();
            $table->string('deposant')->nullable();
            $table->string('receptionneur')->nullable();
            $table->integer('montantverser')->nullable();
            $table->string('codeEtab')->nullable();
            $table->string('session')->nullable();
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
        Schema::dropIfExists('versements');
    }
}
