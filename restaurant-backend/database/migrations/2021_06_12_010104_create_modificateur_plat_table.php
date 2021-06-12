<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificateurPlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modificateur_plat', function (Blueprint $table) {
            $table->primary(['modificateur_id','plat_id']);
            $table->bigInteger('modificateur_id')->unsigned();
            $table->bigInteger('plat_id')->unsigned();
            
            
            $table->foreign('modificateur_id')
                ->references('id')
                ->on('modificateurs');
             $table->foreign('plat_id')
                ->references('id')
                ->on('plats');
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
        Schema::dropIfExists('modificateur_plat');
    }
}
