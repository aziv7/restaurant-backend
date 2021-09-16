<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestedPlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_plats', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('prix');
            $table->string('description');
            $table->boolean('statut')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('requested_plats_custom', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custom_id')->unsigned();
            $table->bigInteger('requested_plats_id')->unsigned();
            $table->foreign('custom_id')
                ->references('id')
                ->on('customs');
            $table->foreign('requested_plats_id')
                ->references('id')
                ->on('requested_plats');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('commande_requested_plats', function (Blueprint $table) {
            $table->id();
            $table->string('commande_id');
            $table->unsignedBigInteger('requested_plat_id');
            $table->integer('quantity')
                ->nullable();
            $table->foreign('requested_plat_id')
                ->references('id')
                ->on('requested_plats')
                ->onDelete('cascade');
            $table->foreign('commande_id')
                ->references('commande_id')
                ->on('commandes')
                ->onDelete('cascade');
        });

        Schema::create('requested_plat_custom_offres', function (Blueprint $table) {
            $table->id();
            $table->string('offre_id');
            $table->unsignedBigInteger('requested_plat_id');
            $table->integer('quantity')
                ->nullable();
            $table->foreign('custom_offre_id')
                ->references('id')
                ->on('custom_offres')
                ->onDelete('cascade');
            $table->foreign('requested_plat_id')
                ->references('id')
                ->on('requested_plats')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requested_plats');
        Schema::dropIfExists('requested_plats_custom');
        Schema::dropIfExists('commande_requested_plats');
    }
}
