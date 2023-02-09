<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mois_id')->constrained()->nullable()->onDelete('cascade');
            $table->string('type')->nullable();
            $table->float('montant')->nullable();
            $table->date('date')->nullable();
            $table->string('codeEtab')->nullable();
            $table->text('motif')->nullable();
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
        Schema::dropIfExists('banques');
    }
}
