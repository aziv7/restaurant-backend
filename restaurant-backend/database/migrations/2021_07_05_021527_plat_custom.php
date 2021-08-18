<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlatCustom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            Schema::create('plat_custom', function (Blueprint $table) {
                $table->primary(['custom_id', 'plat_id']);
                $table->bigInteger('custom_id')->unsigned();
                $table->bigInteger('plat_id')->unsigned();
                $table->foreign('custom_id')
                    ->references('id')
                    ->on('customs');
                $table->foreign('plat_id')
                    ->references('id')
                    ->on('plats');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plat_custom');
    }
}
