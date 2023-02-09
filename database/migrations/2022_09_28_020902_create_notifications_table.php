<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()

    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->text('contenu')->nullable();
            $table->foreignId('user_id')->constrained()->nullable()->onDelete('cascade');
            $table->boolean('statut')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
