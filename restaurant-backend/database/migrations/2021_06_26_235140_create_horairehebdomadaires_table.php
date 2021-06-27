<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorairehebdomadairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horairehebdomadaires', function (Blueprint $table) {
            $table->id();
            $table->string('jour')->unique();
            $table->time('heure_debut');
            $table->time('heure_fermeture');
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horairehebdomadaires');
    }
}
