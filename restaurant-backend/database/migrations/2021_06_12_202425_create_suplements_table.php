<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuplementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('image');
            $table->string('nom');
            $table->double('prix',8,3);
            $table->integer('quantite');
        });
        Schema::create('plat_supplement', function (Blueprint $table) {
            $table->primary(['supplement_id','plat_id']);
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('supplement_id')->unsigned()->onDelete('cascade');
            $table->bigInteger('plat_id')->unsigned()->onDelete('cascade');
            
            
            $table->foreign('supplement_id')
                ->references('id')
                ->on('supplements');
             $table->foreign('plat_id')
                ->references('id')
                ->on('plats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suplements');
    }
}
