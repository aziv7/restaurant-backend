<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
$table->string('description');
$table->string('nom');$table->double('prix');
$table->string('image');
$table->boolean('isDisponible');
        });
        Schema::create('offre_plat', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes(); 
             $table->unsignedBigInteger('plat_id');
            $table->unsignedBigInteger('offre_id');
            $table->foreign('offre_id')
                ->references('id')
                ->on('offres')
                ->onDelete('cascade');
            $table->foreign('plat_id')
                ->references('id')
                ->on('plats')
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
        Schema::dropIfExists('offres');
        Schema::dropIfExists('offre_plat');
    }
}
