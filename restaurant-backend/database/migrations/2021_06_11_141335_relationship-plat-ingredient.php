<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RelationshipPlatIngredient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ingredient_plat', function (Blueprint $table) {
            $table->primary(['ingredient_id','plat_id']);
            $table->bigInteger('ingredient_id')->unsigned();
            $table->bigInteger('plat_id')->unsigned();
            
            $table->timestamps();
            $table->foreign('plat_id')
                ->references('id')
                ->on('plats');
            $table->foreign('ingredient_id')
                ->references('id')
                ->on('ingredients');
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
        //
    }
}
