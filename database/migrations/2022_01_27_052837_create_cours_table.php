<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matiere_id')->constrained()->nullable()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->nullable()->onDelete('cascade');
            $table->foreignId('enseignants_id')->constrained()->nullable()->onDelete('cascade');
            $table->string('chapitre')->nullable();
            $table->text('competence')->nullable();
            $table->string('document')->nullable();
            $table->integer('statut')->nullable();
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
        Schema::dropIfExists('cours');
    }
}
