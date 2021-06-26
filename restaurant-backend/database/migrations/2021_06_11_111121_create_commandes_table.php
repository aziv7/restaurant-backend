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
            $table->enum('status',Statut::getKeys())->default(Statut::getKey(0));
            $table->bigInteger('plat_id')->unsigned();
            $table->foreign('plat_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('quantite');
            $table->timestamp('date_paiement');
            $table->timestamp('date_traitement');
            $table->boolean('ingredient');
            $table->double('prix');
            $table->integer('quantite_supplement')->default(0);
            $table->string('token');
            $table->double('longitude');
            $table->double('latitude');
            $table->softDeletes();
            $table->integer('code_reduction_id')->unsigned()->nullable();
            $table->foreign('code_reduction_id')->references('id')->on('code_reductions')->onDelete('cascade');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
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
