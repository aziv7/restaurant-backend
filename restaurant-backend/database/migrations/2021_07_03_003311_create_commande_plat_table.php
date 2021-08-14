<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandePlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commande_plats', function (Blueprint $table) {
            $table->id();
            $table->string('commande_id');
            $table->unsignedBigInteger('plat_id');
            $table->integer('quantity')->nullable();
            $table->foreign('plat_id')->references('id')->on('plats')->onDelete('cascade');
            $table->foreign('commande_id')->references('commande_id')->on('commandes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commande_plat');
    }
}
