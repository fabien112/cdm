<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabletimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabletimes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jour')->nullable();
            $table->integer('id_heureD')->nullable();
            $table->integer('id_heureF')->nullable();
            // $table->foreignId('matieres_id')->constrained()->nullable()->onDelete('cascade');
            $table->string('matiere')->nullable();
            $table->foreignId('enseignant_id')->constrained()->nullable()->onDelete('cascade');
            $table->foreignId('classe_id')->constrained()->nullable()->onDelete('cascade');
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
        Schema::dropIfExists('tabletimes');
    }
}
