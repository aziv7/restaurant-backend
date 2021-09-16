<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \App\Enums\Statut;
class CreateCommandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->string('commande_id')->primary();
            $table->string('livraison')->nullable();
            $table->string('livraison_address')->nullable();
            $table->enum('status',Statut::getKeys())->default(Statut::getKey(0));
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('date_paiement')->nullable();
            $table->timestamp('date_traitement')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->softDeletes();
            $table->integer('code_reduction_id')->unsigned()->nullable();
            $table->foreign('code_reduction_id')->references('id')->on('code_reductions')->onDelete('cascade');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->double('prix_total')->nullable();
            $table->string('paiement_modality')->nullable();
        });

        Schema::create('commandes_custom_offres', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custom_offre_id')->unsigned();
            $table->string('command_id')->unsigned();
            $table->foreign('custom_offre_id')
                ->references('id')
                ->on('customs');
            $table->foreign('command_id')
                ->references('command_id')
                ->on('commandes');
            $table->softDeletes();
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
        Schema::dropIfExists('commandes');
    }
}
