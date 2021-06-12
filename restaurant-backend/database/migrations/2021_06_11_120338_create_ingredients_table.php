<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('quantite');
            $table->enum('type', ['legume', 'viande','fromage']);
            $table->float('stock', 6, 3);
            $table->bigInteger('modificateur_id')->unsigned()->nullable();
            
            
            $table->foreign('modificateur_id')->references('id')->on('modificateurs')->onDelete('cascade');
            $table->string('image');
            $table->double('prix', 6, 3);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
}
